@props([
    'title' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-gray-200 shadow-sm p-5 md:p-6']) }}>
    @if($title || isset($action))
        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
            <div>
                @if($title)
                    <h3 class="text-sm md:text-base font-bold text-gray-900 tracking-tight">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-xs text-gray-500 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($action))
                <div>{{ $action }}</div>
            @endif
        </div>
    @endif
    {{ $slot }}
</div>
