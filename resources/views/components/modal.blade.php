@props([
    'show',
    'title',
    'maxWidth' => 'max-w-lg',
])

<div x-show="{{ $show }}" x-cloak
     class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
    <div @click.away="{{ $show }} = false"
         class="bg-white rounded-3xl {{ $maxWidth }} w-full p-6 md:p-8 shadow-2xl border border-gray-100 space-y-5 animate-scale-up">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-base font-bold text-gray-900 tracking-tight">
                {{ $title }}
            </h3>
            <button type="button" @click="{{ $show }} = false" class="text-gray-400 hover:text-gray-600 text-lg font-bold p-1">
                ✕
            </button>
        </div>

        <!-- Modal Body / Content -->
        {{ $slot }}
    </div>
</div>
