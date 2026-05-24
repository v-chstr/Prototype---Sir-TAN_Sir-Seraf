<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        $activePeriod = AcademicPeriod::current();

        $history = AcademicPeriod::orderByDesc('started_at')
            ->orderByDesc('id')
            ->get()
            ->map(function ($period) {
                $period->evaluation_count = Evaluation::where('academic_year', $period->academic_year)
                    ->where('semester', $period->semester)
                    ->where('status', 'submitted')
                    ->count();
                return $period;
            });

        $nextPreview = $activePeriod ? $activePeriod->nextPeriod() : [
            'academic_year' => date('Y') . '-' . (date('Y') + 1),
            'semester'      => 'First Semester',
        ];

        return view('admin.settings.index', compact('activePeriod', 'history', 'nextPreview'));
    }

    public function openInitial(Request $request)
    {
        $request->validate([
            'academic_year' => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'semester'      => ['required', 'in:First Semester,Second Semester,Summer'],
        ]);

        if (AcademicPeriod::active()->exists()) {
            return redirect()->route('admin.settings')->with('error', 'An active period already exists. Close it first.');
        }

        AcademicPeriod::create([
            'academic_year' => $request->academic_year,
            'semester'      => $request->semester,
            'is_active'     => true,
            'started_at'    => now(),
        ]);

        return redirect()->route('admin.settings')->with('success', 'Academic period opened successfully.');
    }

    public function transition(Request $request)
    {
        $active = AcademicPeriod::current();

        if (!$active) {
            return redirect()->route('admin.settings')->with('error', 'There is no active period to close.');
        }

        $next = $active->nextPeriod();

        DB::transaction(function () use ($active, $next) {
            $active->update([
                'is_active' => false,
                'ended_at'  => now(),
            ]);

            AcademicPeriod::create([
                'academic_year' => $next['academic_year'],
                'semester'      => $next['semester'],
                'is_active'     => true,
                'started_at'    => now(),
            ]);
        });

        return redirect()->route('admin.settings')->with('success', 'Period closed. New period opened: A.Y. ' . $next['academic_year'] . ' · ' . $next['semester']);
    }

    public function close(Request $request)
    {
        $active = AcademicPeriod::current();

        if (!$active) {
            return redirect()->route('admin.settings')->with('error', 'There is no active period to close.');
        }

        $active->update([
            'is_active' => false,
            'ended_at'  => now(),
        ]);

        return redirect()->route('admin.settings')->with('success', 'Active period closed. No period is currently open.');
    }
}
