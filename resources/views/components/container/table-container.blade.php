@props([
    'title' => null,
])

<div {{ $attributes->merge(['class' => 'bg-surface-card rounded shadow-[0_1px_4px_rgba(0,0,0,0.08)] p-5']) }}>
    @if($title || isset($actions))
        <div class="flex items-center justify-between mb-4">
            @if($title)
                <h5 class="text-base font-semibold text-gray-900 m-0">{{ $title }}</h5>
            @endif
            @if(isset($actions))
                <div>{{ $actions }}</div>
            @endif
        </div>
    @endif
    <div class="overflow-x-auto">
        {{ $slot }}
    </div>
</div>
