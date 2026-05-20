<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\EvaluationCategory;
use App\Models\EvaluationResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                return $this->streamSummaryCsv($exportCategories, 'summary_report.csv');
            }
            return $this->streamEvaluationsCsv($evaluations, 'evaluations_report.csv');
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
        return $this->streamSummaryCsv($categories, 'summary_report.csv');
    }

    private function streamSummaryCsv($categories, string $filename)
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-store, no-cache',
        ];

        $callback = function () use ($categories) {
            $handle = fopen('php://output', 'w');
            // BOM for Excel UTF-8 compatibility
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Category', 'Type', 'Criteria/Question', 'Average Rating', 'Total Responses', 'Rating Label']);

            foreach ($categories as $category) {
                $totalRating   = 0;
                $totalResponses = 0;

                foreach ($category->criteria as $criteria) {
                    $responses = $criteria->responses;
                    $avg   = $responses->avg('rating') ?? 0;
                    $count = $responses->count();

                    fputcsv($handle, [
                        $category->name,
                        ucfirst($category->type),
                        $criteria->question,
                        number_format($avg, 2),
                        $count,
                        $this->getRatingLabel($avg),
                    ]);

                    $totalRating    += $avg * $count;
                    $totalResponses += $count;
                }

                $overallAvg = $totalResponses > 0 ? $totalRating / $totalResponses : 0;
                fputcsv($handle, [
                    $category->name . ' (OVERALL)',
                    ucfirst($category->type),
                    '--- CATEGORY AVERAGE ---',
                    number_format($overallAvg, 2),
                    $category->evaluations->count(),
                    $this->getRatingLabel($overallAvg),
                ]);
                fputcsv($handle, ['', '', '', '', '', '']);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function streamEvaluationsCsv($evaluations, string $filename)
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-store, no-cache',
        ];

        $callback = function () use ($evaluations) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['ID', 'Date', 'Category', 'Type', 'Average Rating', 'Comment', 'Academic Year', 'Semester']);

            foreach ($evaluations as $evaluation) {
                fputcsv($handle, [
                    $evaluation->id,
                    $evaluation->created_at->format('Y-m-d H:i:s'),
                    $evaluation->category->name ?? 'Unknown',
                    ucfirst($evaluation->category->type ?? 'unknown'),
                    number_format($evaluation->average_rating, 2),
                    $evaluation->overall_comment ?? '',
                    $evaluation->academic_year ?? '',
                    $evaluation->semester ?? '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getRatingLabel(float $rating): string
    {
        if ($rating >= 4.5) return 'Excellent';
        if ($rating >= 3.5) return 'Very Good';
        if ($rating >= 2.5) return 'Good';
        if ($rating >= 1.5) return 'Fair';
        return 'Poor';
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
