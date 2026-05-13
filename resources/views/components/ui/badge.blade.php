@props([
    'variant' => 'default',
    'size' => 'md',
])

@php
    $base = 'inline-flex items-center font-medium rounded-sm';

    $sizes = [
        'sm' => 'px-1.5 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-xs',
        'lg' => 'px-3 py-1.5 text-sm',
    ];

    $variants = [
        'default'   => 'bg-gray-100 text-gray-700',
        'primary'   => 'bg-blue-100 text-blue-800',
        'success'   => 'bg-green-100 text-green-800',
        'warning'   => 'bg-amber-100 text-amber-800',
        'danger'    => 'bg-red-100 text-red-800',
        'info'      => 'bg-cyan-100 text-cyan-800',
        'secondary' => 'bg-gray-100 text-gray-600',
    ];

    $classes = $base . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['default']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
