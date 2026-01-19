@extends('layouts.app')

@section('title', 'Register - SPUP Evaluation System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg animate-fade-in">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus display-4" style="color: var(--spup-primary);"></i>
                        <h3 class="mt-3 fw-bold">Create Account</h3>
                        <p class="text-muted">Register to start evaluating</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Enter your full name" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="Enter your email" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="role_id" class="form-label">I am a <span class="text-danger">*</span></label>
                            <select class="form-select @error('role_id') is-invalid @enderror" 
                                    id="role_id" name="role_id" required>
                                <option value="">Select your role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-3" id="student-fields" style="display: none;">
                            <div class="col">
                                <label for="student_id" class="form-label">Student ID</label>
                                <input type="text" class="form-control" id="student_id" name="student_id" 
                                       value="{{ old('student_id') }}" placeholder="e.g., 2024-00001">
                            </div>
                        </div>

                        <div class="row mb-3" id="employee-fields" style="display: none;">
                            <div class="col-md-6">
                                <label for="employee_id" class="form-label">Employee ID</label>
                                <input type="text" class="form-control" id="employee_id" name="employee_id" 
                                       value="{{ old('employee_id') }}" placeholder="e.g., EMP-001">
                            </div>
                            <div class="col-md-6">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control" id="department" name="department" 
                                       value="{{ old('department') }}" placeholder="e.g., IT Department">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                       placeholder="Enter your phone number">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Create password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control" id="password_confirmation" 
                                           name="password_confirmation" placeholder="Confirm password" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-spup w-100 py-2 mb-3">
                            <i class="bi bi-person-plus me-2"></i>Create Account
                        </button>

                        <p class="text-center text-muted mb-0">
                            Already have an account? <a href="{{ route('login') }}" style="color: var(--spup-primary);">Login here</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('role_id').addEventListener('change', function() {
        const studentFields = document.getElementById('student-fields');
        const employeeFields = document.getElementById('employee-fields');
        const selectedText = this.options[this.selectedIndex].text.toLowerCase();

        studentFields.style.display = 'none';
        employeeFields.style.display = 'none';

        if (selectedText.includes('student')) {
            studentFields.style.display = 'flex';
        } else if (selectedText.includes('employee')) {
            employeeFields.style.display = 'flex';
        }
    });

    // Trigger on page load for old values
    document.getElementById('role_id').dispatchEvent(new Event('change'));
</script>
@endpush
