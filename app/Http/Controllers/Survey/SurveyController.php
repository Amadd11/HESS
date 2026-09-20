<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Models\Demographic;
use App\Models\Period;
use App\Models\Question;
use App\Services\SurveyResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SurveyController extends Controller
{
    /**
     * Tampilkan halaman kuesioner survei.
     */
    public function index(): View
    {
        return view('survey.index', [
            'period' => Period::active()->first(),
            'questions' => Question::with('category')->active()->get(),
            ...Demographic::getGroupedOptions(),
        ]);
    }

    /**
     * Simpan jawaban survei responden anonim.
     */
    public function submit(StoreSurveyRequest $request, SurveyResponseService $service): JsonResponse|RedirectResponse
    {
        $period = Period::active()->first();

        if (! $period) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Tidak ada periode survei yang sedang aktif.'], 422)
                : back()->withErrors(['period' => 'Tidak ada periode survei yang sedang aktif.']);
        }

        $service->save($period, $request->validated());

        return $request->expectsJson()
            ? response()->json(['success' => true, 'redirect' => route('survey.finish')])
            : redirect()->route('survey.finish')->with('survey_completed', true);
    }

    /**
     * Halaman ucapan terima kasih setelah pengisian selesai.
     */
    public function finish(): View
    {
        return view('survey.finish');
    }
}