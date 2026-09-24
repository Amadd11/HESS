<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Question\StoreQuestionRequest;
use App\Http\Requests\Admin\Question\UpdateQuestionRequest;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionController extends Controller
{
    /**
     * Tampilkan daftar master pertanyaan dengan filter pencarian dan kategori.
     */
    public function index(Request $request): View
    {
        $query = Question::with('category')->orderBy('order');


        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('scale')) {
            $scale = $request->string('scale')->value();
            if (in_array($scale, ['satisfaction', 'agreement'], true)) {
                $query->where('scale', $scale);
            }
        }

        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $query->where(fn ($q) => $q->where('code', 'like', "%{$search}%")->orWhere('text', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->string('status') === 'active');
        }

        $stats = [
            'total' => Question::count(),
            'active' => Question::where('is_active', true)->count(),
            'inactive' => Question::where('is_active', false)->count(),
        ];

        return view('admin.questions.index', [
            'questions' => $query->paginate(15)->withQueryString(),
            'categories' => Category::orderBy('order')->get(),
            'stats' => $stats,
            'nextOrder' => (int) (Question::max('order') ?? 0) + 1,
        ]);
    }

    /**
     * Simpan pertanyaan baru ke database.
     */
    public function store(StoreQuestionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['order'] = $validated['order'] ?? (Question::max('order') + 1);
        $validated['is_active'] = $request->boolean('is_active', true);

        Question::create($validated);

        return redirect()->route('admin.questions.index')->with('success', 'Pertanyaan baru berhasil ditambahkan!');
    }

    /**
     * Perbarui data butir pertanyaan.
     */
    public function update(UpdateQuestionRequest $request, Question $question): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        $question->update($validated);

        return redirect()->route('admin.questions.index')->with('success', 'Data pertanyaan berhasil diperbarui!');
    }

    /**
     * Toggle status aktif/nonaktif pertanyaan dengan cepat.
     */
    public function toggle(Question $question, Request $request): JsonResponse|RedirectResponse
    {
        $question->update(['is_active' => ! $question->is_active]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $question->is_active,
                'message' => "Pertanyaan {$question->code} berhasil diubah statusnya.",
            ]);
        }

        return back()->with('success', "Status pertanyaan {$question->code} berhasil diubah!");
    }

    /**
     * Hapus pertanyaan secara aman (Soft Delete).
     */
    public function destroy(Question $question): RedirectResponse
    {
        $code = $question->code;
        $question->delete();

        return redirect()->route('admin.questions.index')->with('success', "Pertanyaan {$code} berhasil dihapus.");
    }
}
