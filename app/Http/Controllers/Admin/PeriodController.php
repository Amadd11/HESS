<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Period\StorePeriodRequest;
use App\Http\Requests\Admin\Period\UpdatePeriodRequest;
use App\Models\Period;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PeriodController extends Controller
{
    /**
     * Tampilkan daftar periode survei kepuasan pegawai.
     */
    public function index(): View
    {
        $periods = Period::withCount('responses')->orderByDesc('start_date')->get();

        return view('admin.periods.index', compact('periods'));
    }

    /**
     * Simpan periode survei baru.
     */
    public function store(StorePeriodRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']) . '-' . time();
        $data['is_active'] = $request->boolean('is_active');

        // Jika periode baru diset aktif, nonaktifkan periode aktif sebelumnya
        if ($data['is_active']) {
            Period::where('is_active', true)->update(['is_active' => false]);
        }

        Period::create($data);

        return redirect()->route('admin.periods.index')->with('success', 'Periode survei baru berhasil ditambahkan!');
    }

    /**
     * Perbarui data periode survei.
     */
    public function update(UpdatePeriodRequest $request, Period $period): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        if ($data['is_active'] && ! $period->is_active) {
            Period::where('id', '!=', $period->id)->update(['is_active' => false]);
        }

        $period->update($data);

        return redirect()->route('admin.periods.index')->with('success', 'Data periode survei berhasil diperbarui!');
    }

    /**
     * Jadikan periode ini sebagai periode aktif survei.
     */
    public function activate(Period $period): RedirectResponse
    {
        Period::where('id', '!=', $period->id)->update(['is_active' => false]);
        $period->update(['is_active' => true]);

        return back()->with('success', "Periode '{$period->name}' sekarang berstatus aktif!");
    }

    /**
     * Hapus periode survei (Soft Delete).
     */
    public function destroy(Period $period): RedirectResponse
    {
        if ($period->is_active) {
            return back()->with('error', 'Periode yang sedang aktif tidak dapat dihapus. Aktifkan periode lain terlebih dahulu.');
        }

        $period->delete();

        return redirect()->route('admin.periods.index')->with('success', 'Periode survei berhasil dihapus.');
    }
}
