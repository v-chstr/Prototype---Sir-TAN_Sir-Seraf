@props([
    'type' => 'success',
    'dismissible' => true,
])

@php
    $styles = [
        'success' => 'bg-green-50 text-green-800 border-green-200',
        'error'   => 'bg-red-50 text-red-800 border-red-200',
        'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
        'info'    => 'bg-blue-50 text-blue-800 border-blue-200',
    ];
    $classes = $styles[$type] ?? $styles['info'];
@endphp

<div {{ $attributes->merge(['class' => "flex items-center justify-between border rounded px-4 py-3 text-sm $classes", 'role' => 'alert']) }}>
    <span>{{ $slot }}</span>
    @if($dismissible)
        <button type="button" onclick="this.parentElement.remove()" class="ml-3 opacity-60 hover:opacity-100 transition-opacity">
            <x-heroicon-m-x-mark class="w-4 h-4" />
        </button>
    @endif
</div>
