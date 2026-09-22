{{-- Empty State --}}
<div class="bg-white rounded-3xl p-12 border border-gray-200/90 shadow-xs text-center space-y-4 max-w-md mx-auto my-8">
    <div class="w-16 h-16 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
    </div>
    <div class="space-y-1">
        <h4 class="text-base font-bold text-gray-900">Belum Ada Masukan Responden</h4>
        <p class="text-xs text-gray-500 leading-relaxed">
            Tidak ditemukan data umpan balik terbuka untuk periode atau filter demografi yang sedang Anda pilih.
        </p>
    </div>
    <div class="pt-2">
        <a href="{{ route('admin.sentiment.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 text-primary-700 hover:bg-primary-100 rounded-xl text-xs font-bold transition">
            <span>Reset Seluruh Filter</span>
        </a>
    </div>
</div>
