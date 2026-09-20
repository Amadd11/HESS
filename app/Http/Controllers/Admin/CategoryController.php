<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar master kategori kuesioner.
     */
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('questions')->orderBy('order')->get(),
        ]);
    }

    /**
     * Simpan kategori baru.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['order'] = $validated['order'] ?? (Category::max('order') + 1);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    /**
     * Perbarui data kategori.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()->route('admin.categories.index')->with('success', 'Data kategori berhasil diperbarui!');
    }

    /**
     * Hapus kategori (Soft Delete).
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->questions()->count() > 0) {
            return back()->with('error', "Kategori '{$category->name}' tidak dapat dihapus karena masih memiliki {$category->questions()->count()} butir pertanyaan.");
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
