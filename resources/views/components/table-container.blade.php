<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden']) }}>
    <div class="overflow-x-auto">
        {{ $slot }}
    </div>
</div>
