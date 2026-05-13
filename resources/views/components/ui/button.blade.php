@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'icon' => null,
])

@php
    $base = 'inline-flex items-center justify-center font-medium transition-colors duration-200 rounded cursor-pointer';

    $sizes = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
    ];

    $variants = [
        'primary'         => 'bg-spup-primary text-white hover:bg-spup-dark',
        'accent'          => 'bg-spup-accent text-gray-900 hover:bg-amber-400',
        'danger'          => 'bg-rating-poor text-white hover:bg-red-700',
        'outline'         => 'border border-surface-border text-gray-700 bg-white hover:bg-surface-muted',
        'outline-primary' => 'border border-spup-primary text-spup-primary bg-white hover:bg-spup-light',
        'outline-danger'  => 'border border-rating-poor text-rating-poor bg-white hover:bg-red-50',
        'ghost'           => 'text-gray-600 hover:bg-surface-muted',
        'success'         => 'bg-spup-primary text-white hover:bg-spup-dark',
    ];

    $classes = $base . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <x-dynamic-component :component="$icon" class="w-4 h-4 {{ $slot->isNotEmpty() ? 'mr-1.5' : '' }}" />
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <x-dynamic-component :component="$icon" class="w-4 h-4 {{ $slot->isNotEmpty() ? 'mr-1.5' : '' }}" />
        @endif
        {{ $slot }}
    </button>
@endif
