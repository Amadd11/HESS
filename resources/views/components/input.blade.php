@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'value' => null,
    'required' => false,
])

<div class="space-y-1">
    @if($label)
        <label @if($name) for="{{ $name }}" @endif class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        @if($name) name="{{ $name }}" id="{{ $name }}" @endif
        @if($value !== null) value="{{ old($name, $value) }}" @endif
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'w-full h-10 px-3.5 rounded-xl border border-gray-300 text-xs text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition']) }}
    >

    @if($name)
        @error($name)
            <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
        @enderror
    @endif
</div>
