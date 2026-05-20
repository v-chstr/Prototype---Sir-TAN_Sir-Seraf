@extends('layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Evaluation Categories')

@section('content')
<div class="row g-4">
    <div class="col-12 d-flex justify-content-end mb-2">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-spup btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Add Category
        </a>
    </div>

    @if(session('success'))
        <div class="col-12"><div class="alert alert-success py-2">{{ session('success') }}</div></div>
    @endif
    @if(session('error'))
        <div class="col-12"><div class="alert alert-danger py-2">{{ session('error') }}</div></div>
    @endif

    @if(session('success'))
        <div class="col-12"><div class="alert alert-success py-2">{{ session('success') }}</div></div>
    @endif
    @if(session('error'))
        <div class="col-12"><div class="alert alert-danger py-2">{{ session('error') }}</div></div>
    @endif

    @foreach(['standard' => 'Standards Categories', 'office' => 'Office Categories'] as $type => $label)
    <div class="col-12">
        <div class="card {{ !$loop->last ? 'mb-4' : '' }}">
            <div class="card-header bg-white">
                <h5 class="mb-0">{{ $label }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Questions</th>
                                <th>Evaluations</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories->where('type', $type) as $category)
                                <tr>
                                    <td>
                                        <i class="{{ $category->icon ?? 'bi bi-star' }} me-2" style="color: var(--spup-primary);"></i>
                                        {{ $category->name }}
                                    </td>
                                    <td>{{ Str::limit($category->description, 50) }}</td>
                                    <td><span class="badge bg-secondary">{{ $category->criteria_count }}</span></td>
                                    <td><span class="badge bg-info">{{ $category->evaluations_count }}</span></td>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.categories.toggle', $category->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $category->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="bi {{ $category->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete"
                                                onclick="confirmDelete({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->evaluations_count }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No {{ strtolower($label) }} found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:4px;">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-semibold">Delete Category</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1">Are you sure you want to delete <strong id="deleteCategoryName"></strong>?</p>
                <p class="text-muted small mb-0">This will also delete all its questions. This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name, evalCount) {
    if (evalCount > 0) {
        alert('Cannot delete "' + name + '" because it has ' + evalCount + ' existing evaluation(s).');
        return;
    }
    document.getElementById('deleteCategoryName').textContent = name;
    document.getElementById('deleteForm').action = '/admin/categories/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
