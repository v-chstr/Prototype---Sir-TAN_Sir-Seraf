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
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">Student ID</label>
                                <input type="text" class="form-control" id="student_id" name="student_id"
                                       value="{{ old('student_id') }}" placeholder="e.g., 2024-00001">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select gender...</option>
                                    @foreach($genders as $g)
                                        <option value="{{ $g }}" {{ old('gender') == $g ? 'selected' : '' }}>{{ $g }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="student_department" class="form-label">Department / School</label>
                                <select class="form-select" id="student_department" name="department">
                                    <option value="">Select department...</option>
                                    @foreach($departments as $code => $label)
                                        <option value="{{ $code }}" {{ old('department') == $code ? 'selected' : '' }}>{{ $code }} — {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="course" class="form-label">Course</label>
                                <select class="form-select" id="course" name="course" disabled>
                                    <option value="">Select department first...</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="year_level" class="form-label">Year Level</label>
                                <select class="form-select" id="year_level" name="year_level">
                                    <option value="">Select...</option>
                                    @foreach($yearLevels as $yl)
                                        <option value="{{ $yl }}" {{ old('year_level') == $yl ? 'selected' : '' }}>{{ $yl }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3" id="employee-fields" style="display: none;">
                            <div class="col-md-6">
                                <label for="employee_id" class="form-label">Employee ID</label>
                                <input type="text" class="form-control" id="employee_id" name="employee_id" 
                                       value="{{ old('employee_id') }}" placeholder="e.g., EMP-001">
                            </div>
                            <div class="col-md-6">
                                <label for="employee_department" class="form-label">Department</label>
                                <input type="text" class="form-control" id="employee_department" name="department"
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

<script>
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || (window.performance && window.performance.getEntriesByType('navigation')[0]?.type === 'back_forward')) {
            window.location.reload();
        }
    });
</script>
@endsection

@push('scripts')
<script>
    const COURSES_BY_DEPT = @json($courses);
    const OLD_COURSE = @json(old('course'));

    function toggleRoleFields() {
        const roleSelect = document.getElementById('role_id');
        const studentFields = document.getElementById('student-fields');
        const employeeFields = document.getElementById('employee-fields');
        const selectedText = roleSelect.options[roleSelect.selectedIndex].text.toLowerCase();

        studentFields.style.display = 'none';
        employeeFields.style.display = 'none';

        // Disable inputs in hidden sections so duplicate "department" names don't conflict
        document.getElementById('student_department').disabled = true;
        document.getElementById('course').disabled = true;
        document.getElementById('year_level').disabled = true;
        document.getElementById('gender').disabled = true;
        document.getElementById('student_id').disabled = true;
        document.getElementById('employee_department').disabled = true;
        document.getElementById('employee_id').disabled = true;

        if (selectedText.includes('student')) {
            studentFields.style.display = 'flex';
            document.getElementById('student_department').disabled = false;
            document.getElementById('year_level').disabled = false;
            document.getElementById('gender').disabled = false;
            document.getElementById('student_id').disabled = false;
            populateCourses();
        } else if (selectedText.includes('employee')) {
            employeeFields.style.display = 'flex';
            document.getElementById('employee_department').disabled = false;
            document.getElementById('employee_id').disabled = false;
        }
    }

    function populateCourses() {
        const dept = document.getElementById('student_department').value;
        const courseSelect = document.getElementById('course');
        courseSelect.innerHTML = '';

        if (!dept || !COURSES_BY_DEPT[dept]) {
            courseSelect.innerHTML = '<option value="">Select department first...</option>';
            courseSelect.disabled = true;
            return;
        }

        courseSelect.disabled = false;
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Select course...';
        courseSelect.appendChild(placeholder);

        Object.entries(COURSES_BY_DEPT[dept]).forEach(([code, label]) => {
            const opt = document.createElement('option');
            opt.value = code;
            opt.textContent = code + ' — ' + label;
            if (OLD_COURSE && OLD_COURSE === code) opt.selected = true;
            courseSelect.appendChild(opt);
        });
    }

    document.getElementById('role_id').addEventListener('change', toggleRoleFields);
    document.getElementById('student_department').addEventListener('change', populateCourses);

    // Trigger on page load for old values
    toggleRoleFields();
</script>
@endpush
