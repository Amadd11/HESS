@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
])

@php
    $variantClasses = match($variant) {
        'primary' => 'bg-primary-600 hover:bg-primary-700 text-white shadow-sm shadow-primary-600/20 active:scale-[0.99]',
        'secondary' => 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 active:scale-[0.99]',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-sm shadow-red-600/20 active:scale-[0.99]',
        'emerald', 'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm shadow-emerald-600/20 active:scale-[0.99]',
        'ghost' => 'text-gray-600 hover:text-gray-900 hover:bg-gray-100',
        default => 'bg-primary-600 hover:bg-primary-700 text-white shadow-sm shadow-primary-600/20',
    };

    $sizeClasses = match($size) {
        'xs' => 'h-8 px-2.5 text-[11px] gap-1',
        'sm' => 'h-9 px-3 text-xs gap-1.5',
        'lg' => 'h-12 px-6 text-sm gap-2.5',
        default => 'h-10 px-4 text-xs font-bold gap-2',
    };

    $baseClasses = "inline-flex items-center justify-center rounded-xl font-bold transition select-none cursor-pointer {$variantClasses} {$sizeClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        {{ $slot }}
    </button>
@endif
