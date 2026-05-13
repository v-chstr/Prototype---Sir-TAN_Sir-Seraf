@extends('layouts.admin')

@section('title', 'Add Category')
@section('page-title', 'Add Evaluation Category')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">New Category</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Admissions Office">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="office" {{ old('type') === 'office' ? 'selected' : '' }}>Office</option>
                            <option value="standard" {{ old('type') === 'standard' ? 'selected' : '' }}>Standard</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Brief description of what this category evaluates">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Icon Class (Bootstrap Icons)</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon') }}" placeholder="e.g. bi-building (optional)">
                        <small class="text-muted">Leave blank for default. Browse icons at <code>icons.getbootstrap.com</code></small>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label">Evaluation Questions</label>
                        <div id="criteria-list">
                            @if(old('criteria'))
                                @foreach(old('criteria') as $i => $q)
                                    <div class="input-group mb-2 criteria-row">
                                        <span class="input-group-text">{{ $i + 1 }}</span>
                                        <input type="text" name="criteria[]" class="form-control" value="{{ $q }}" required placeholder="Enter evaluation question">
                                        <button type="button" class="btn btn-outline-danger remove-criteria" title="Remove"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                @endforeach
                            @else
                                @for($i = 0; $i < 3; $i++)
                                    <div class="input-group mb-2 criteria-row">
                                        <span class="input-group-text">{{ $i + 1 }}</span>
                                        <input type="text" name="criteria[]" class="form-control" required placeholder="Enter evaluation question">
                                        <button type="button" class="btn btn-outline-danger remove-criteria" title="Remove"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                @endfor
                            @endif
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="add-criteria">
                            <i class="bi bi-plus me-1"></i>Add Question
                        </button>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Back
                        </a>
                        <button type="submit" class="btn btn-spup">
                            <i class="bi bi-check-lg me-1"></i>Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('add-criteria').addEventListener('click', function () {
        const list = document.getElementById('criteria-list');
        const count = list.querySelectorAll('.criteria-row').length + 1;
        const div = document.createElement('div');
        div.className = 'input-group mb-2 criteria-row';
        div.innerHTML = `
            <span class="input-group-text">${count}</span>
            <input type="text" name="criteria[]" class="form-control" required placeholder="Enter evaluation question">
            <button type="button" class="btn btn-outline-danger remove-criteria" title="Remove"><i class="bi bi-x-lg"></i></button>
        `;
        list.appendChild(div);
    });

    document.getElementById('criteria-list').addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-criteria');
        if (btn) {
            const rows = this.querySelectorAll('.criteria-row');
            if (rows.length > 1) {
                btn.closest('.criteria-row').remove();
                this.querySelectorAll('.criteria-row').forEach(function (row, i) {
                    row.querySelector('.input-group-text').textContent = i + 1;
                });
            }
        }
    });
</script>
@endpush
@endsection
