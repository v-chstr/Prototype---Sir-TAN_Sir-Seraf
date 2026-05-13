@props([
    'id',
    'title' => 'Are you sure?',
    'message' => 'This action cannot be undone.',
    'action' => null,
    'method' => 'POST',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
])

<x-modals.modal :id="$id" :title="$title">
    <p class="text-sm text-gray-600 mb-0">{{ $message }}</p>

    <x-slot:footer>
        <x-ui.button variant="outline" onclick="closeModal('{{ $id }}')">{{ $cancelText }}</x-ui.button>
        @if($action)
            <form action="{{ $action }}" method="POST" class="inline">
                @csrf
                @if($method !== 'POST')
                    @method($method)
                @endif
                <x-ui.button variant="danger" type="submit">{{ $confirmText }}</x-ui.button>
            </form>
        @else
            {{ $slot }}
        @endif
    </x-slot:footer>
</x-modals.modal>
