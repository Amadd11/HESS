@props([
    'title',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4']) }}>
    <div>
        <h2 class="text-base font-bold text-gray-900 tracking-tight">{{ $title }}</h2>
        @if($description)
            <p class="text-xs text-gray-500 mt-0.5">{{ $description }}</p>
        @endif
    </div>

    @if($slot->isNotEmpty())
        <div class="flex items-center gap-2">
            {{ $slot }}
        </div>
    @endif
</div>
