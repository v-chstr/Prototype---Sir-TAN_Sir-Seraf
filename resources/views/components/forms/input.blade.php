@props([
    'name',
    'label' => null,
    'type' => 'text',
    'required' => false,
])

<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->except('class')->merge(['class' => 'w-full border border-surface-border rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-spup-primary focus:border-spup-primary transition-colors']) }}
    />
    @error($name)
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
