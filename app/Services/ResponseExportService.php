<?php

namespace App\Services;

use App\Models\Period;
use App\Models\Response;
use Illuminate\Database\Eloquent\Builder;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResponseExportService
{
    /**
     * Bangun query Response dengan filter multi-dimensi demografi, periode, NPS, dan pencarian.
     *
     * @param  array<string, mixed>  $filters
     */
    public function buildFilteredQuery(array $filters = [], ?int $periodId = null): Builder
    {
        $query = Response::query()->with('period');

        if ($periodId) {
            $query->where('period_id', $periodId);
        } elseif (! empty($filters['period_id']) && $filters['period_id'] !== 'all') {
            $query->where('period_id', (int) $filters['period_id']);
        }

        foreach (['profession', 'unit', 'status', 'tenure'] as $field) {
            if (! empty($filters[$field])) {
                $query->where($field, trim((string) $filters[$field]));
            }
        }

        if (! empty($filters['nps_category'])) {
            $query->where('nps_category', trim((string) $filters['nps_category']));
        }

        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('like_text', 'like', "%{$search}%")
                    ->orWhere('improve_text', 'like', "%{$search}%")
                    ->orWhere('unit', 'like', "%{$search}%")
                    ->orWhere('profession', 'like', "%{$search}%");
            });
        }

        return $query;
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
                    'Profesi' => $r->profession ?: '-',
                    'Unit Kerja' => $r->unit ?: '-',
                    'Status Kepegawaian' => $r->status ?: '-',
                    'Lama Bekerja' => $tenure ?: '-',
                    'Kepuasan Umum MSQ (%)' => $r->general_score !== null ? (float) number_format((float) $r->general_score, 1, '.', '') : '-',
                    'Kepuasan Intrinsik (%)' => $r->intrinsic_score !== null ? (float) number_format((float) $r->intrinsic_score, 1, '.', '') : '-',
                    'Kepuasan Ekstrinsik (%)' => $r->extrinsic_score !== null ? (float) number_format((float) $r->extrinsic_score, 1, '.', '') : '-',
                    'Faktor Rumah Sakit (%)' => $r->hospital_score !== null ? (float) number_format((float) $r->hospital_score, 1, '.', '') : '-',
                    'Skor Keseluruhan (1-5)' => $r->overall_score !== null ? (int) $r->overall_score : '-',
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
