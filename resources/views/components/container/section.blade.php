@props([
    'title' => null,
])

<div {{ $attributes->merge(['class' => '']) }}>
    @if($title)
        <h5 class="text-base font-semibold text-gray-900 mb-4">{{ $title }}</h5>
    @endif
    {{ $slot }}
</div>
