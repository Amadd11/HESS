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
use Illuminate\Support\Facades\DB;
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

        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $query->where(fn($q) => $q->where('code', 'like', "%{$search}%")->orWhere('text', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->string('status') === 'active');
        }

        $stats = [
            'total' => Question::count(),
            'active' => Question::where('is_active', true)->count(),
            'inactive' => Question::where('is_active', false)->count(),
        ];

        $maxCodeNum = Question::withTrashed()->get()->map(function ($q) {
            return preg_match('/^H(\d+)$/i', $q->code, $m) ? (int) $m[1] : 0;
        })->max() ?? 0;
        $nextCode = 'H' . ($maxCodeNum + 1);

        return view('admin.questions.index', [
            'questions' => $query->paginate(15)->withQueryString(),
            'categories' => Category::orderBy('order')->get(),
            'stats' => $stats,
            'nextOrder' => (int) (Question::max('order') ?? 0) + 1,
            'nextCode' => $nextCode,
        ]);
    }


    /**
     * Simpan pertanyaan baru ke database dengan pergeseran nomor urutan tampil otomatis.
     */
    public function store(StoreQuestionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $targetOrder = isset($validated['order']) && (int) $validated['order'] > 0
            ? (int) $validated['order']
            : (int) (Question::max('order') ?? 0) + 1;

        $targetCode = strtoupper(trim((string) $validated['code']));
        $validated['order'] = $targetOrder;
        $validated['code'] = $targetCode;
        $validated['is_active'] = $request->boolean('is_active', true);

        $shiftedCount = 0;

        DB::transaction(function () use ($validated, $targetOrder, &$shiftedCount) {
            // Geser nomor urutan tampil (order) untuk butir yang urutannya >= $targetOrder
            $questionsToShift = Question::where('order', '>=', $targetOrder)
                ->orderByDesc('order')
                ->get();

            if ($questionsToShift->isNotEmpty()) {
                $shiftedCount = $questionsToShift->count();
                foreach ($questionsToShift as $q) {
                    $q->update(['order' => $q->order + 1]);
                }
            }

            Question::create($validated);
        });

        $message = $shiftedCount > 0
            ? "Pertanyaan baru {$targetCode} (Urutan Tampil {$targetOrder}) berhasil ditambahkan! {$shiftedCount} butir setelahnya telah bergeser urutan tampilnya."
            : "Pertanyaan baru {$targetCode} (Urutan Tampil {$targetOrder}) berhasil ditambahkan!";

        return redirect()->route('admin.questions.index')->with('success', $message);
    }

    /**
     * Perbarui data butir pertanyaan dengan pergeseran otomatis jika nomor urutan tampil diubah.
     */
    public function update(UpdateQuestionRequest $request, Question $question): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');
        $newOrder = isset($validated['order']) && (int) $validated['order'] > 0
            ? (int) $validated['order']
            : (int) $question->order;

        $oldOrder = (int) $question->order;
        $newCode = strtoupper(trim((string) $validated['code']));
        $validated['order'] = $newOrder;
        $validated['code'] = $newCode;

        DB::transaction(function () use ($question, $validated, $oldOrder, $newOrder) {
            if ($newOrder !== $oldOrder) {
                if ($newOrder < $oldOrder) {
                    // Geser ke bawah (+1) untuk butir antara $newOrder dan $oldOrder - 1
                    $between = Question::where('order', '>=', $newOrder)
                        ->where('order', '<', $oldOrder)
                        ->where('id', '!=', $question->id)
                        ->orderByDesc('order')
                        ->get();

                    foreach ($between as $item) {
                        $item->update(['order' => $item->order + 1]);
                    }
                } else {
                    // Geser ke atas (-1) untuk butir antara $oldOrder + 1 dan $newOrder
                    $between = Question::where('order', '>', $oldOrder)
                        ->where('order', '<=', $newOrder)
                        ->where('id', '!=', $question->id)
                        ->orderBy('order')
                        ->get();

                    foreach ($between as $item) {
                        $item->update(['order' => $item->order - 1]);
                    }
                }
            }

            $question->update($validated);
        });

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
        $deletedOrder = (int) $question->order;

        DB::transaction(function () use ($question, $deletedOrder) {
            $question->delete();
            // Rapatkan urutan tampil butir setelahnya agar tidak meninggalkan celah (gap)
            Question::where('order', '>', $deletedOrder)->decrement('order');
        });

        return redirect()->route('admin.questions.index')->with('success', "Pertanyaan {$code} berhasil dihapus.");
    }
}
