@props([
    'show',
    'title',
    'maxWidth' => 'max-w-lg',
])

<div x-show="{{ $show }}" x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60">
    <div @click.away="{{ $show }} = false"
         x-show="{{ $show }}"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-3xl {{ $maxWidth }} w-full p-6 md:p-8 shadow-2xl border border-gray-100 space-y-5">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-base font-bold text-gray-900 tracking-tight">
                {{ $title }}
            </h3>
            <button type="button" @click="{{ $show }} = false" class="text-gray-400 hover:text-gray-600 text-lg font-bold p-1 cursor-pointer">
                ✕
            </button>
        </div>

        <!-- Modal Body / Content -->
        {{ $slot }}
    </div>
</div>
