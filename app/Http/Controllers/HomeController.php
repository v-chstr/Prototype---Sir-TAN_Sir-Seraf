<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\Evaluation;
use App\Models\EvaluationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function standards()
    {
        $categories   = EvaluationCategory::standards()->active()->get();
        $activePeriod = AcademicPeriod::current();
        $evaluatedIds = $this->evaluatedCategoryIds($activePeriod);

        return view('standards.index', compact('categories', 'evaluatedIds', 'activePeriod'));
    }

    public function offices()
    {
        $categories   = EvaluationCategory::offices()->active()->get();
        $activePeriod = AcademicPeriod::current();
        $evaluatedIds = $this->evaluatedCategoryIds($activePeriod);

        return view('offices.index', compact('categories', 'evaluatedIds', 'activePeriod'));
    }

    public function contact()
    {
        return view('contact');
    }

    private function evaluatedCategoryIds(?AcademicPeriod $activePeriod)
    {
        if (!Auth::check() || !$activePeriod) {
            return collect();
        }

        return Evaluation::where('user_id', Auth::id())
            ->where('academic_year', $activePeriod->academic_year)
            ->where('semester', $activePeriod->semester)
            ->pluck('category_id');
    }
}
