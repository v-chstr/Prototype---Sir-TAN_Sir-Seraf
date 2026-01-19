<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationCategory;
use App\Models\EvaluationResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    public function show($id)
    {
        $category = EvaluationCategory::with(['criteria' => function ($query) {
            $query->active()->ordered();
        }])->findOrFail($id);

        return view('evaluation.show', compact('category'));
    }

    public function store(Request $request, $id)
    {
        $category = EvaluationCategory::with(['criteria' => function ($query) {
            $query->active()->ordered();
        }])->findOrFail($id);

        $rules = [];
        foreach ($category->criteria as $criteria) {
            $rules["rating_{$criteria->id}"] = ['required', 'integer', 'min:1', 'max:5'];
            $rules["comment_{$criteria->id}"] = ['nullable', 'string', 'max:500'];
        }
        $rules['overall_comment'] = ['nullable', 'string', 'max:1000'];

        $request->validate($rules);

        DB::transaction(function () use ($request, $category) {
            $evaluation = Evaluation::create([
                'user_id' => Auth::id(),
                'category_id' => $category->id,
                'academic_year' => date('Y') . '-' . (date('Y') + 1),
                'semester' => $this->getCurrentSemester(),
                'overall_comment' => $request->overall_comment,
                'status' => 'submitted',
            ]);

            foreach ($category->criteria as $criteria) {
                EvaluationResponse::create([
                    'evaluation_id' => $evaluation->id,
                    'criteria_id' => $criteria->id,
                    'rating' => $request->input("rating_{$criteria->id}"),
                    'comment' => $request->input("comment_{$criteria->id}"),
                ]);
            }
        });

        return redirect()->route('evaluation.thank-you')->with('success', 'Thank you for your evaluation!');
    }

    public function thankYou()
    {
        return view('evaluation.thank-you');
    }

    private function getCurrentSemester()
    {
        $month = date('n');
        if ($month >= 6 && $month <= 10) {
            return 'First Semester';
        } elseif ($month >= 11 || $month <= 3) {
            return 'Second Semester';
        } else {
            return 'Summer';
        }
    }
}
