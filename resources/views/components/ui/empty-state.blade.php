@props([
    'title' => null,
    'message' => 'No items found',
])

<div {{ $attributes->merge(['class' => 'text-center py-10 text-gray-400']) }}>
    @if($title)
        <p class="text-lg font-medium text-gray-500 mb-1">{{ $title }}</p>
    @endif
    <p class="text-sm">{{ $message }}</p>
</div>
