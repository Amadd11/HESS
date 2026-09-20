@props([
    'type' => 'success',
    'message' => null,
])

@php
    $colors = match($type) {
        'error', 'danger' => 'bg-red-50 border-red-500 text-red-800',
        'warning' => 'bg-amber-50 border-amber-500 text-amber-800',
        'info' => 'bg-blue-50 border-blue-500 text-blue-800',
        default => 'bg-emerald-50 border-emerald-500 text-emerald-800',
    };

    $iconColor = match($type) {
        'error', 'danger' => 'text-red-600',
        'warning' => 'text-amber-600',
        'info' => 'text-blue-600',
        default => 'text-emerald-600',
    };
@endphp

<div {{ $attributes->merge(['class' => "mb-6 border-l-4 p-4 rounded-xl text-xs md:text-sm flex items-center justify-between shadow-sm {$colors}"]) }}>
    <div class="flex items-center gap-2.5">
        @if($type === 'error' || $type === 'danger')
            <svg class="w-4 h-4 {{ $iconColor }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        @else
            <svg class="w-4 h-4 {{ $iconColor }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        @endif
        <span>{{ $message ?? $slot }}</span>
    </div>
</div>
