<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demographic;
use App\Models\Period;
use App\Models\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResponseController extends Controller
{
    /**
     * Tampilkan daftar seluruh respon survei responden dengan filter.
     */
    public function index(Request $request): View
    {
        $query = Response::with('period')->orderByDesc('completed_at');

        if ($request->filled('period_id')) {
            $query->where('period_id', $request->integer('period_id'));
        }

        if ($request->filled('directorate')) {
            $query->where('directorate', $request->string('directorate')->value());
        }

        if ($request->filled('unit')) {
            $query->where('unit', $request->string('unit')->value());
        }

        if ($request->filled('profession')) {
            $query->where('profession', $request->string('profession')->value());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->value());
        }

        if ($request->filled('tenure')) {
            $query->where('tenure', $request->string('tenure')->value());
        }

        if ($request->filled('nps_category')) {
            $query->where('nps_category', $request->string('nps_category')->value());
        }

        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('like_text', 'like', "%{$search}%")
                    ->orWhere('improve_text', 'like', "%{$search}%")
                    ->orWhere('directorate', 'like', "%{$search}%")
                    ->orWhere('unit', 'like', "%{$search}%")
                    ->orWhere('profession', 'like', "%{$search}%");
            });
        }

        $periods = Period::orderByDesc('start_date')->get();
        $demographics = Demographic::getGroupedOptions();

        $stats = [
            'total' => Response::count(),
            'promoters' => Response::where('nps_category', 'promoter')->count(),
            'passives' => Response::where('nps_category', 'passive')->count(),
            'detractors' => Response::where('nps_category', 'detractor')->count(),
        ];

        $perPage = $request->integer('per_page', 50);
        if (! in_array($perPage, [15, 25, 50, 100])) {
            $perPage = 50;
        }

        return view('admin.responses.index', [
            'responses' => $query->paginate($perPage)->withQueryString(),
            'perPage' => $perPage,
            'periods' => $periods,
            'demographics' => $demographics,
            'stats' => $stats,
        ]);
    }

    /**
     * Ambil data detail respon beserta seluruh butir jawaban terurut (untuk modal detail).
     */
    public function show(Response $response): JsonResponse
    {
        $response->load([
            'period',
            'answers.question.category',
        ]);

        $sortedAnswers = $response->answers->sortBy(function ($a) {
            $code = (string) ($a->question?->code ?? '');
            preg_match('/\d+/', $code, $matches);

            return isset($matches[0]) ? (int) $matches[0] : 999;
        })->values();

        return response()->json([
            'response' => $response,
            'answers' => $sortedAnswers->map(fn ($a) => [
                'id' => $a->id,
                'score' => $a->score,
                'question_code' => $a->question?->code,
                'question_text' => $a->question?->text,
                'category_name' => $a->question?->category?->name,
                'category_type' => $a->question?->category?->type,
            ]),
        ]);
    }

    /**
     * Hapus data respon survei (beserta jawaban terkait via cascade).
     */
    public function destroy(Response $response): RedirectResponse
    {
        $response->delete();

        return back()->with('success', 'Data respon survei berhasil dihapus.');
    }
}
