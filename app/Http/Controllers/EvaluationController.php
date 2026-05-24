<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
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

        $activePeriod = AcademicPeriod::current();

        if (!$activePeriod) {
            return redirect()->back()->with('already_evaluated', 'Evaluation period is not yet open. Please check back later.');
        }

        $alreadyEvaluated = Evaluation::where('user_id', Auth::id())
            ->where('category_id', $category->id)
            ->where('academic_year', $activePeriod->academic_year)
            ->where('semester', $activePeriod->semester)
            ->exists();

        if ($alreadyEvaluated) {
            return redirect()->back()->with('already_evaluated', 'You have already submitted an evaluation for "' . $category->name . '" this ' . $activePeriod->semester . '.');
        }

        return view('evaluation.show', compact('category', 'activePeriod'));
    }

    public function store(Request $request, $id)
    {
        $category = EvaluationCategory::with(['criteria' => function ($query) {
            $query->active()->ordered();
        }])->findOrFail($id);

        $activePeriod = AcademicPeriod::current();

        if (!$activePeriod) {
            return redirect()->back()->with('already_evaluated', 'Evaluation period is not yet open.');
        }

        $alreadySubmitted = Evaluation::where('user_id', Auth::id())
            ->where('category_id', $category->id)
            ->where('academic_year', $activePeriod->academic_year)
            ->where('semester', $activePeriod->semester)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->back()->with('already_evaluated', 'You have already submitted an evaluation for "' . $category->name . '" this ' . $activePeriod->semester . '.');
        }

        $rules = [];
        foreach ($category->criteria as $criteria) {
            $rules["rating_{$criteria->id}"] = ['required', 'integer', 'min:1', 'max:5'];
            $rules["comment_{$criteria->id}"] = ['nullable', 'string', 'max:500'];
        }
        $rules['overall_comment'] = ['nullable', 'string', 'max:1000'];

        $request->validate($rules);

        DB::transaction(function () use ($request, $category, $activePeriod) {
            $evaluation = Evaluation::create([
                'user_id'         => Auth::id(),
                'category_id'     => $category->id,
                'academic_year'   => $activePeriod->academic_year,
                'semester'        => $activePeriod->semester,
                'overall_comment' => strip_tags((string) $request->overall_comment),
                'status'          => 'submitted',
            ]);

            foreach ($category->criteria as $criteria) {
                EvaluationResponse::create([
                    'evaluation_id' => $evaluation->id,
                    'criteria_id'   => $criteria->id,
                    'rating'        => $request->input("rating_{$criteria->id}"),
                    'comment'       => strip_tags((string) $request->input("comment_{$criteria->id}")),
                ]);
            }
        });

        return redirect()->route('evaluation.thank-you')->with('success', 'Thank you for your evaluation!');
    }

    public function thankYou()
    {
        return view('evaluation.thank-you');
    }
}
