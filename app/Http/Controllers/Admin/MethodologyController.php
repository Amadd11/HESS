<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Question;
use Illuminate\View\View;

class MethodologyController extends Controller
{
    /**
     * Tampilkan Panduan Metodologi & Rumus Perhitungan dengan bahasa yang ramah dan mudah dipahami.
     */
    public function index(): View
    {
        $categories = Category::withCount(['questions' => fn ($q) => $q->whereNull('deleted_at')->where('is_active', true)])
            ->orderBy('order')
            ->get();

        $questionsCount = Question::where('is_active', true)->count();

        return view('admin.methodology.index', [
            'categories' => $categories,
            'questionsCount' => $questionsCount,
        ]);
    }
}
