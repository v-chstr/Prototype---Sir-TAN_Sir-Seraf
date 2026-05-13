@props([
    'title' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-surface-card border border-surface-border rounded shadow-[0_1px_4px_rgba(0,0,0,0.08)]']) }}>
    @if($title || isset($header))
        <div class="px-5 py-3 border-b border-surface-border flex items-center justify-between">
            @if($title)
                <h5 class="text-base font-semibold text-gray-900 m-0">{{ $title }}</h5>
            @endif
            @if(isset($header))
                {{ $header }}
            @endif
        </div>
    @endif
    <div class="{{ $padding ? 'p-5' : '' }}">
        {{ $slot }}
    </div>
    @if(isset($footer))
        <div class="px-5 py-3 border-t border-surface-border">
            {{ $footer }}
        </div>
    @endif
</div>
