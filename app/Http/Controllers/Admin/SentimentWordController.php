<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SentimentWord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SentimentWordController extends Controller
{
    /**
     * Display a listing of custom sentiment words.
     */
    public function index(Request $request): View
    {
        $query = SentimentWord::query()->latest();

        if ($request->filled('sentiment')) {
            $sentiment = $request->string('sentiment')->value();
            if (in_array($sentiment, ['positive', 'neutral', 'negative'], true)) {
                $query->where('sentiment', $sentiment);
            }
        }

        if ($request->filled('status')) {
            $status = $request->string('status')->value();
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('search')) {
            $search = strtolower(trim($request->string('search')));
            $query->where(function ($q) use ($search) {
                $q->where('word', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $stats = [
            'total' => SentimentWord::count(),
            'positive' => SentimentWord::where('sentiment', 'positive')->count(),
            'neutral' => SentimentWord::where('sentiment', 'neutral')->count(),
            'negative' => SentimentWord::where('sentiment', 'negative')->count(),
            'active' => SentimentWord::where('is_active', true)->count(),
        ];

        $words = $query->paginate(20)->withQueryString();

        return view('admin.sentiment-words.index', [
            'words' => $words,
            'stats' => $stats,
        ]);
    }

    /**
     * Store a newly created sentiment word.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'word' => ['required', 'string', 'max:60', 'unique:sentiment_words,word'],
            'sentiment' => ['required', Rule::in(['positive', 'neutral', 'negative'])],
            'is_active' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['word'] = strtolower(trim($validated['word']));
        $validated['is_active'] = $request->boolean('is_active', true);

        SentimentWord::create($validated);

        return redirect()->route('admin.sentiment-words.index')->with('success', "Kata '{$validated['word']}' berhasil ditambahkan ke kamus sentimen!");
    }

    /**
     * Update the specified sentiment word.
     */
    public function update(Request $request, SentimentWord $sentimentWord): RedirectResponse
    {
        $validated = $request->validate([
            'word' => ['required', 'string', 'max:60', Rule::unique('sentiment_words', 'word')->ignore($sentimentWord->id)],
            'sentiment' => ['required', Rule::in(['positive', 'neutral', 'negative'])],
            'is_active' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['word'] = strtolower(trim($validated['word']));
        $validated['is_active'] = $request->boolean('is_active', true);

        $sentimentWord->update($validated);

        return redirect()->route('admin.sentiment-words.index')->with('success', "Kosakata '{$sentimentWord->word}' berhasil diperbarui!");
    }

    /**
     * Toggle active status.
     */
    public function toggle(SentimentWord $sentimentWord, Request $request): RedirectResponse|JsonResponse
    {
        $sentimentWord->update([
            'is_active' => ! $sentimentWord->is_active,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $sentimentWord->is_active,
                'message' => "Status kosakata '{$sentimentWord->word}' berhasil diubah.",
            ]);
        }

        return redirect()->back()->with('success', "Status kosakata '{$sentimentWord->word}' berhasil diubah.");
    }

    /**
     * Remove the specified sentiment word.
     */
    public function destroy(SentimentWord $sentimentWord): RedirectResponse
    {
        $word = $sentimentWord->word;
        $sentimentWord->delete();

        return redirect()->route('admin.sentiment-words.index')->with('success', "Kosakata '{$word}' berhasil dihapus!");
    }
}
