{{--
    Dropdown hook — togglable dropdown menu.
    Usage:
    <x-hooks.dropdown>
        <x-slot:trigger>
            <x-ui.button variant="outline" size="sm">Options</x-ui.button>
        </x-slot:trigger>
        <a href="#" class="block px-4 py-2 text-sm hover:bg-surface-muted">Edit</a>
        <a href="#" class="block px-4 py-2 text-sm hover:bg-surface-muted">Delete</a>
    </x-hooks.dropdown>
--}}
@props([
    'align' => 'right',
])

@php
    $alignClass = $align === 'left' ? 'left-0' : 'right-0';
@endphp

<div class="relative inline-block" x-data="{ open: false }">
    <div @click="open = !open" @click.outside="open = false">
        {{ $trigger }}
    </div>
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute {{ $alignClass }} mt-1 w-48 bg-surface-card border border-surface-border rounded shadow-lg z-20 py-1"
        style="display: none;"
    >
        {{ $slot }}
    </div>
</div>
