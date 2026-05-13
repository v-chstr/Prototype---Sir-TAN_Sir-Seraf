<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public const DEPARTMENTS = [
        'S.I.T.E' => 'School of Information Technology and Engineering',
        'SASTE' => 'School of Arts and Sciences and Teacher Education',
        'SBAHM' => 'School of Business Administration and Hospitality Management',
        'SNAHS' => 'School of Nursing and Allied Health Sciences',
        'SOM' => 'School of Medicine',
    ];

    public const COURSES = [
        'S.I.T.E' => [
            'BSIT' => 'BS Information Technology',
            'BSCS' => 'BS Computer Science',
            'BSCpE' => 'BS Computer Engineering',
            'BSECE' => 'BS Electronics Engineering',
        ],
        'SASTE' => [
            'AB Comm' => 'AB Communication',
            'AB Psych' => 'AB Psychology',
            'AB English' => 'AB English',
            'BSED' => 'Bachelor of Secondary Education',
            'BEED' => 'Bachelor of Elementary Education',
        ],
        'SBAHM' => [
            'BSBA' => 'BS Business Administration',
            'BSA' => 'BS Accountancy',
            'BSHM' => 'BS Hospitality Management',
            'BSTM' => 'BS Tourism Management',
        ],
        'SNAHS' => [
            'BSN' => 'BS Nursing',
            'BSMT' => 'BS Medical Technology',
            'BSP' => 'BS Pharmacy',
        ],
        'SOM' => [
            'MD' => 'Doctor of Medicine',
        ],
    ];

    public const YEAR_LEVELS = [
        '1st Year',
        '2nd Year',
        '3rd Year',
        '4th Year',
        '5th Year',
    ];

    public const GENDERS = [
        'Male',
        'Female',
        'Prefer not to say',
    ];

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        $roles = Role::all();
        $departments = self::DEPARTMENTS;
        $courses = self::COURSES;
        $yearLevels = self::YEAR_LEVELS;
        $genders = self::GENDERS;
        return view('auth.register', compact('roles', 'departments', 'courses', 'yearLevels', 'genders'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
            'student_id' => ['nullable', 'string', 'max:50'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
            'department' => ['nullable', 'string', 'max:100'],
            'course' => ['nullable', 'string', 'max:100'],
            'year_level' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'student_id' => $request->student_id,
            'employee_id' => $request->employee_id,
            'phone' => $request->phone,
            'department' => $request->department,
            'course' => $request->course,
            'year_level' => $request->year_level,
            'gender' => $request->gender,
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
