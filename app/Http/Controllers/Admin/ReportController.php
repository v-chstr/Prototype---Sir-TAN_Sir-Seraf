<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\EvaluationCategory;
use App\Models\EvaluationResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EvaluationsExport;
use App\Exports\SummaryReportExport;

class ReportController extends Controller
{
    public function index()
    {
        $categories = EvaluationCategory::active()->get();
        return view('admin.reports.index', compact('categories'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => ['required', 'in:detailed,summary,category'],
            'category_id' => ['nullable', 'exists:evaluation_categories,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'format' => ['required', 'in:view,excel'],
        ]);

        $query = Evaluation::with(['user', 'category', 'responses.criteria'])
            ->submitted();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $evaluations = $query->get();

        if ($request->format === 'excel') {
            if ($request->report_type === 'summary') {
                $exportCategories = EvaluationCategory::with(['criteria.responses', 'evaluations'])
                    ->active()
                    ->when($request->filled('category_id'), fn($q) => $q->where('id', $request->category_id))
                    ->get();
                return Excel::download(new SummaryReportExport($exportCategories), 'summary_report.xlsx');
            }
            return Excel::download(new EvaluationsExport($evaluations), 'evaluations_report.xlsx');
        }

        // Generate statistics for view
        $stats = $this->generateStats($evaluations);
        $categoryStats = $this->getCategoryStats($query->clone());
        $criteriaStats = $this->getCriteriaStats($evaluations);

        return view('admin.reports.result', compact(
            'evaluations',
            'stats',
            'categoryStats',
            'criteriaStats',
            'request'
        ));
    }

    public function summaryReport()
    {
        $categories = EvaluationCategory::with(['criteria', 'evaluations.responses'])->active()->get();
        
        $summary = [];
        foreach ($categories as $category) {
            $totalResponses = 0;
            $totalRating = 0;
            $criteriaStats = [];

            foreach ($category->criteria as $criteria) {
                $responses = EvaluationResponse::where('criteria_id', $criteria->id)->get();
                $avgRating = $responses->avg('rating') ?? 0;
                $responseCount = $responses->count();

                $criteriaStats[] = [
                    'question' => $criteria->question,
                    'avg_rating' => round($avgRating, 2),
                    'response_count' => $responseCount,
                ];

                $totalRating += $avgRating * $responseCount;
                $totalResponses += $responseCount;
            }

            $summary[] = [
                'category' => $category->name,
                'type' => $category->type,
                'total_evaluations' => $category->evaluations->count(),
                'overall_avg' => $totalResponses > 0 ? round($totalRating / $totalResponses, 2) : 0,
                'criteria_stats' => $criteriaStats,
            ];
        }

        return view('admin.reports.summary', compact('summary'));
    }

    public function exportSummary()
    {
        $categories = EvaluationCategory::with(['criteria.responses', 'evaluations'])->active()->get();
        return Excel::download(new SummaryReportExport($categories), 'summary_report.xlsx');
    }

    private function generateStats($evaluations)
    {
        $totalEvaluations = $evaluations->count();
        $totalResponses = 0;
        $totalRating = 0;

        foreach ($evaluations as $evaluation) {
            foreach ($evaluation->responses as $response) {
                $totalRating += $response->rating;
                $totalResponses++;
            }
        }

        return [
            'total_evaluations' => $totalEvaluations,
            'total_responses' => $totalResponses,
            'average_rating' => $totalResponses > 0 ? round($totalRating / $totalResponses, 2) : 0,
        ];
    }

    private function getCategoryStats($query)
    {
        return $query->select('category_id', DB::raw('COUNT(*) as count'))
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->category->name ?? 'Unknown',
                    'count' => $item->count,
                ];
            });
    }

    private function getCriteriaStats($evaluations)
    {
        $criteriaRatings = [];

        foreach ($evaluations as $evaluation) {
            foreach ($evaluation->responses as $response) {
                $criteriaId = $response->criteria_id;
                if (!isset($criteriaRatings[$criteriaId])) {
                    $criteriaRatings[$criteriaId] = [
                        'question' => $response->criteria->question ?? 'Unknown',
                        'category' => $response->criteria->category->name ?? 'Unknown',
                        'ratings' => [],
                    ];
                }
                $criteriaRatings[$criteriaId]['ratings'][] = $response->rating;
            }
        }

        return collect($criteriaRatings)->map(function ($item) {
            $ratings = collect($item['ratings']);
            return [
                'question' => $item['question'],
                'category' => $item['category'],
                'avg_rating' => round($ratings->avg(), 2),
                'total_responses' => $ratings->count(),
            ];
        })->values();
    }
}
