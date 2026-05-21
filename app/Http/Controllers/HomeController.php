<?php

namespace App\Http\Controllers;

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
        $categories = EvaluationCategory::standards()->active()->get();
        $evaluatedIds = Auth::check()
            ? Evaluation::where('user_id', Auth::id())->pluck('category_id')
            : collect();
        return view('standards.index', compact('categories', 'evaluatedIds'));
    }

    public function offices()
    {
        $categories = EvaluationCategory::offices()->active()->get();
        $evaluatedIds = Auth::check()
            ? Evaluation::where('user_id', Auth::id())->pluck('category_id')
            : collect();
        return view('offices.index', compact('categories', 'evaluatedIds'));
    }

    public function contact()
    {
        return view('contact');
    }
}
