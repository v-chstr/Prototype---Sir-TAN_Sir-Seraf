@props([
    'name',
    'label' => null,
    'options' => [],
    'placeholder' => null,
    'selected' => null,
    'required' => false,
])

<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->except('class')->merge(['class' => 'w-full border border-surface-border rounded px-3 py-2 text-sm bg-white focus:outline-none focus:ring-1 focus:ring-spup-primary focus:border-spup-primary transition-colors']) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $optionLabel)
            <option value="{{ $value }}" {{ (string)$selected === (string)$value ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
        {{ $slot }}
    </select>
    @error($name)
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
