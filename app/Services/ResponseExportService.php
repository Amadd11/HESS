<?php

namespace App\Services;

use App\Models\Period;
use App\Models\Response;
use Illuminate\Database\Eloquent\Builder;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResponseExportService
{
    public function __construct(
        protected ?DashboardService $dashboardService = null
    ) {
        $this->dashboardService ??= app(DashboardService::class);
    }

    /**
     * Bangun query Response dengan filter multi-dimensi demografi, periode, NPS, dan pencarian.
     *
     * @param  array<string, mixed>  $filters
     */
    public function buildFilteredQuery(array $filters = [], ?int $periodId = null): Builder
    {
        return $this->dashboardService->buildFilteredQuery($filters, $periodId);
    }

    /**
     * Ekspor ringkasan data respon survei ke format Excel (.xlsx) dengan streaming generator FastExcel.
     *
     * @param  array<string, mixed>  $filters
     */
    public function exportExcel(array $filters = []): StreamedResponse
    {
        $periodId = null;
        if (! empty($filters['period_id']) && $filters['period_id'] !== 'all') {
            $periodId = (int) $filters['period_id'];
        } elseif (isset($filters['from_dashboard'])) {
            $periodId = Period::active()->value('id');
        }

        $period = $periodId ? Period::find($periodId) : null;
        $periodSlug = $period?->slug ?? 'semua_periode';
        $timestamp = now()->format('Ymd_His');
        $filename = "hess_ringkasan_respon_{$periodSlug}_{$timestamp}.xlsx";

        $query = $this->buildFilteredQuery($filters, $periodId)
            ->with(['period'])
            ->orderByDesc('id');

        $rowsGenerator = function () use ($query) {
            foreach ($query->cursor() as $r) {
                $likeText = trim(preg_replace('/\s+/', ' ', (string) ($r->like_text ?? '')));
                $improveText = trim(preg_replace('/\s+/', ' ', (string) ($r->improve_text ?? '')));
                $tenure = str_replace(['–', '—'], '-', (string) ($r->tenure ?? '-'));

                yield [
                    'ID Respon' => $r->id,
                    'Periode Survei' => $r->period?->name ?? ($r->period_id ? 'Periode #'.$r->period_id : '-'),
                    'Waktu Pengisian' => $r->completed_at?->format('Y-m-d H:i:s') ?? $r->created_at?->format('Y-m-d H:i:s') ?? '-',
                    'Direktorat' => $r->directorate ?: '-',
                    'Unit Kerja' => $r->unit ?: '-',
                    'Profesi' => $r->profession ?: '-',
                    'Status Kepegawaian' => $r->status ?: '-',
                    'Lama Bekerja' => $tenure ?: '-',
                    'Usia' => $r->age ?: '-',
                    'Jenis Kelamin' => $r->gender ?: '-',
                    'Pendidikan' => $r->education ?: '-',
                    'Jumlah Pendapatan' => $r->income ?: '-',
                    'Indeks Kepuasan Pegawai (%)' => $r->general_score !== null ? (float) number_format((float) $r->general_score, 1, '.', '') : '-',
                    'eNPS (0-10)' => $r->nps_score !== null ? (int) $r->nps_score : '-',
                    'Kategori eNPS' => $r->nps_category ? ucfirst($r->nps_category) : '-',
                    'Hal yang Disukai' => $likeText ?: '-',
                    'Hal yang Perlu Diperbaiki' => $improveText ?: '-',
                ];
            }
        };

        return (new FastExcel($rowsGenerator()))->download($filename);
    }
}
