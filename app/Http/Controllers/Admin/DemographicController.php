<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demographic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DemographicController extends Controller
{
    /**
     * Tampilkan daftar master opsi demografi responden kuesioner.
     */
    public function index(Request $request): View
    {
        $currentType = $request->query('type', 'unit');
        if (! in_array($currentType, ['directorate', 'unit', 'profession', 'status', 'tenure', 'all'], true)) {
            $currentType = 'unit';
        }

        $query = Demographic::query();

        if ($currentType !== 'all') {
            $query->type($currentType);
        }

        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->string('status')->value() === 'active');
        }

        $demographics = $query->orderBy('order')->orderBy('id')->paginate(20)->withQueryString();

        $stats = [
            'total' => Demographic::count(),
            'directorate' => Demographic::type('directorate')->count(),
            'unit' => Demographic::type('unit')->count(),
            'profession' => Demographic::type('profession')->count(),
            'status' => Demographic::type('status')->count(),
            'tenure' => Demographic::type('tenure')->count(),
        ];

        return view('admin.demographics.index', [
            'demographics' => $demographics,
            'currentType' => $currentType,
            'stats' => $stats,
        ]);
    }

    /**
     * Simpan opsi demografi baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', Rule::in(['directorate', 'unit', 'profession', 'status', 'tenure'])],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('demographics')->where('type', $request->input('type')),
            ],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.unique' => 'Nama opsi demografi ini sudah terdaftar pada kelompok tersebut.',
        ]);

        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active', true);

        Demographic::create($validated);

        return back()->with('success', 'Opsi demografi baru berhasil ditambahkan.');
    }

    /**
     * Perbarui opsi demografi yang ada.
     */
    public function update(Request $request, Demographic $demographic): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('demographics')
                    ->where('type', $demographic->type)
                    ->ignore($demographic->id),
            ],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.unique' => 'Nama opsi demografi ini sudah digunakan pada kelompok tersebut.',
        ]);

        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active', true);

        $demographic->update($validated);

        return back()->with('success', 'Opsi demografi berhasil diperbarui.');
    }

    /**
     * Aktifkan / nonaktifkan opsi demografi dengan cepat.
     */
    public function toggle(Demographic $demographic): RedirectResponse
    {
        $demographic->update([
            'is_active' => ! $demographic->is_active,
        ]);

        $statusText = $demographic->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Status opsi '{$demographic->name}' berhasil {$statusText}.");
    }

    /**
     * Hapus opsi demografi.
     */
    public function destroy(Demographic $demographic): RedirectResponse
    {
        $demographic->delete();

        return back()->with('success', 'Opsi demografi berhasil dihapus.');
    }
}
