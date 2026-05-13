@props([
    'id',
    'title' => null,
    'maxWidth' => 'lg',
])

@php
    $widths = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
    ];
    $width = $widths[$maxWidth] ?? $widths['lg'];
@endphp

<div
    id="{{ $id }}"
    class="fixed inset-0 z-50 hidden items-center justify-center"
    aria-modal="true"
    role="dialog"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('{{ $id }}').classList.add('hidden');document.getElementById('{{ $id }}').classList.remove('flex');"></div>

    {{-- Panel --}}
    <div class="relative bg-surface-card rounded shadow-lg {{ $width }} w-full mx-4">
        @if($title)
            <div class="flex items-center justify-between px-5 py-3 border-b border-surface-border">
                <h5 class="text-base font-semibold text-gray-900 m-0">{{ $title }}</h5>
                <button type="button" onclick="document.getElementById('{{ $id }}').classList.add('hidden');document.getElementById('{{ $id }}').classList.remove('flex');" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <x-heroicon-m-x-mark class="w-5 h-5" />
                </button>
            </div>
        @endif
        <div class="p-5">
            {{ $slot }}
        </div>
        @if(isset($footer))
            <div class="flex items-center justify-end gap-2 px-5 py-3 border-t border-surface-border">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
window.openModal = function(id) {
    const el = document.getElementById(id);
    if (el) { el.classList.remove('hidden'); el.classList.add('flex'); }
};
window.closeModal = function(id) {
    const el = document.getElementById(id);
    if (el) { el.classList.add('hidden'); el.classList.remove('flex'); }
};
</script>
@endpush
