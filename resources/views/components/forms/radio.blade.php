@props([
    'name',
    'label' => null,
    'type' => 'radio',
    'value' => null,
    'checked' => false,
])

<label class="inline-flex items-center gap-2 cursor-pointer text-sm">
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $value }}"
        {{ $checked ? 'checked' : '' }}
        {{ $attributes->merge(['class' => 'text-spup-primary focus:ring-spup-primary']) }}
    />
    <span>{{ $label ?? $slot }}</span>
</label>
