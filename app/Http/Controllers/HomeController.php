<?php

namespace App\Http\Controllers;

use App\Models\EvaluationCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function standards()
    {
        $categories = EvaluationCategory::standards()->active()->get();
        return view('standards.index', compact('categories'));
    }

    public function offices()
    {
        $categories = EvaluationCategory::offices()->active()->get();
        return view('offices.index', compact('categories'));
    }

    public function contact()
    {
        return view('contact');
    }
}
