{{--
    Tooltip hook — hover tooltip using CSS-only approach.
    Usage: <x-hooks.tooltip text="More info">Hover me</x-hooks.tooltip>
--}}
@props([
    'text',
    'position' => 'top',
])

<span class="relative inline-flex group" {{ $attributes }}>
    {{ $slot }}
    <span class="
        pointer-events-none absolute z-30 rounded bg-gray-900 text-white text-xs px-2 py-1 whitespace-nowrap
        opacity-0 group-hover:opacity-100 transition-opacity duration-150
        {{ $position === 'top' ? 'bottom-full left-1/2 -translate-x-1/2 mb-1' : '' }}
        {{ $position === 'bottom' ? 'top-full left-1/2 -translate-x-1/2 mt-1' : '' }}
        {{ $position === 'left' ? 'right-full top-1/2 -translate-y-1/2 mr-1' : '' }}
        {{ $position === 'right' ? 'left-full top-1/2 -translate-y-1/2 ml-1' : '' }}
    ">{{ $text }}</span>
</span>
