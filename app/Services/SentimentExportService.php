<?php

namespace App\Services;

use App\Models\Period;
use App\Models\Response;
use Illuminate\Database\Eloquent\Builder;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SentimentExportService
{
    public function __construct(
        protected SentimentAnalysisService $sentimentAnalysisService
    ) {}

    /**
     * Ekspor data feedback sentimen ke format Excel (.xlsx) dengan FastExcel streaming.
     *
     * @param  array<string, mixed>  $filters
     */
    public function exportExcel(array $filters = []): StreamedResponse
    {
        $periodId = ! empty($filters['period_id']) ? (int) $filters['period_id'] : null;
        $period = $periodId ? Period::find($periodId) : null;
        $periodSlug = $period?->slug ?? 'semua_periode';
        $timestamp = now()->format('Ymd_His');
        $filename = "hess_analisis_sentimen_{$periodSlug}_{$timestamp}.xlsx";

        $query = Response::query()->with('period');

        if ($periodId) {
            $query->where('period_id', $periodId);
        }

        foreach (['directorate', 'unit', 'profession', 'status', 'tenure'] as $col) {
            if (! empty($filters[$col])) {
                $query->where($col, trim((string) $filters[$col]));
            }
        }

        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $query->where(function (Builder $q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('like_text', 'like', "%{$search}%")
                    ->orWhere('improve_text', 'like', "%{$search}%")
                    ->orWhere('directorate', 'like', "%{$search}%")
                    ->orWhere('unit', 'like', "%{$search}%")
                    ->orWhere('profession', 'like', "%{$search}%");
            });
        }

        // Ambil hanya yang memiliki feedback teks
        $query->where(function (Builder $q) {
            $q->whereNotNull('like_text')->orWhereNotNull('improve_text');
        })->orderByDesc('id');

        $rowsGenerator = function () use ($query, $filters) {
            foreach ($query->lazy(250) as $response) {
                $analysis = $this->sentimentAnalysisService->classifyResponse($response);

                // Filter sentimen spesifik jika diminta
                if (! empty($filters['sentiment']) && $filters['sentiment'] !== 'all') {
                    if ($analysis['sentiment'] !== $filters['sentiment']) {
                        continue;
                    }
                }

                yield [
                    'ID Respon' => '#'.$response->id,
                    'Periode Survei' => $response->period?->name ?? '-',
                    'Sentimen' => match ($analysis['sentiment']) {
                        'positive' => 'Positif',
                        'negative' => 'Negatif',
                        default => 'Netral',
                    },
                    'Hal yang Disukai' => $response->like_text ?? '-',
                    'Saran Perbaikan' => $response->improve_text ?? '-',
                    'Direktorat' => $response->directorate ?? '-',
                    'Unit Kerja' => $response->unit ?? '-',
                    'Profesi' => $response->profession ?? '-',
                    'Status Pegawai' => $response->status ?? '-',
                    'Masa Kerja' => $response->tenure ?? '-',
                    'Tanggal Survei' => $response->completed_at ? $response->completed_at->format('d/m/Y H:i') : $response->created_at->format('d/m/Y H:i'),
                ];
            }
        };

        return (new FastExcel($rowsGenerator()))->download($filename);
    }
}
