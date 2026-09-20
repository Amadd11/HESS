@props([
    'color' => 'primary',
    'size' => 'xs',
    'dot' => false,
])

@php
    $colorClasses = match($color) {
        'emerald', 'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'amber', 'warning' => 'bg-amber-50 text-amber-700 border-amber-200',
        'red', 'danger' => 'bg-red-50 text-red-700 border-red-200',
        'blue', 'info' => 'bg-blue-50 text-blue-700 border-blue-200',
        'purple' => 'bg-purple-50 text-purple-700 border-purple-200',
        'gray' => 'bg-gray-100 text-gray-600 border-gray-200',
        default => 'bg-primary-50 text-primary-700 border-primary-200',
    };

    $dotColors = match($color) {
        'emerald', 'success' => 'bg-emerald-500',
        'amber', 'warning' => 'bg-amber-500',
        'red', 'danger' => 'bg-red-500',
        'blue', 'info' => 'bg-blue-500',
        'purple' => 'bg-purple-500',
        'gray' => 'bg-gray-400',
        default => 'bg-primary-500',
    };

    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1 text-xs',
        default => 'px-2 py-0.5 text-[10px] md:text-[11px]',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full font-extrabold border {$sizeClasses} {$colorClasses}"]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotColors }}"></span>
    @endif
    <span>{{ $slot }}</span>
</span>
