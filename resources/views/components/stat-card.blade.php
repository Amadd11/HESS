@props([
    'title',
    'value',
    'badge' => null,
    'badgeColor' => 'primary',
    'subtext' => null,
    'progress' => null,
    'progressColor' => 'bg-primary-600',
    'valueColor' => 'text-gray-900',
])

<div {{ $attributes->merge(['class' => 'bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-2']) }}>
    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block truncate">{{ $title }}</span>

    <div class="flex items-baseline justify-between gap-2">
        <span class="text-3xl font-black tracking-tight {{ $valueColor }}">{{ $value }}</span>
        @if($badge)
            <x-badge :color="$badgeColor">
                {{ $badge }}
            </x-badge>
        @endif
    </div>

    @if($subtext)
        <div class="text-[11px] text-gray-400 truncate">{{ $subtext }}</div>
    @endif

    {{ $slot }}

    @if($progress !== null)
        <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all duration-300 {{ $progressColor }}" style="width: {{ min($progress, 100) }}%"></div>
        </div>
    @endif
</div>
