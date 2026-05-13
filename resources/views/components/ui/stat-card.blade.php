@props([
    'label' => null,
    'value' => 0,
    'format' => true,
])

<div {{ $attributes->merge(['class' => 'border border-surface-border rounded bg-surface-card px-4 py-3 transition-shadow hover:shadow-sm']) }}>
    @if($label)
        <small class="text-gray-500 text-xs block mb-1">{{ $label }}</small>
    @endif
    <h3 class="text-2xl font-semibold text-gray-900 mb-0">
        {{ $format ? number_format($value) : $value }}
    </h3>
    {{ $slot }}
</div>
