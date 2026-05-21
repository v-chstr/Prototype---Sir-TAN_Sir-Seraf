<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\EvaluationCategory;
use App\Models\EvaluationResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'format' => ['required', 'in:view,excel,pdf'],
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

        if ($request->format === 'pdf') {
            $stats         = $this->generateStats($evaluations);
            $categoryStats = $this->getCategoryStats($query->clone());
            $criteriaStats = $this->getCriteriaStats($evaluations);

            $pdf = Pdf::loadView('admin.reports.pdf', compact('evaluations', 'stats', 'categoryStats', 'criteriaStats', 'request'))
                ->setPaper('a4', 'portrait');

            return $pdf->download('evaluation_report_' . now()->format('Ymd_His') . '.pdf');
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

    public function exportSummaryPdf()
    {
        $categories = EvaluationCategory::with(['criteria', 'evaluations.responses'])->active()->get();

        $summary = [];
        foreach ($categories as $category) {
            $totalResponses = 0;
            $totalRating    = 0;
            $criteriaStats  = [];

            foreach ($category->criteria as $criteria) {
                $responses   = EvaluationResponse::where('criteria_id', $criteria->id)->get();
                $avgRating   = $responses->avg('rating') ?? 0;
                $responseCount = $responses->count();

                $criteriaStats[] = [
                    'question'       => $criteria->question,
                    'avg_rating'     => round($avgRating, 2),
                    'response_count' => $responseCount,
                ];

                $totalRating    += $avgRating * $responseCount;
                $totalResponses += $responseCount;
            }

            $summary[] = [
                'category'          => $category->name,
                'type'              => $category->type,
                'total_evaluations' => $category->evaluations->count(),
                'overall_avg'       => $totalResponses > 0 ? round($totalRating / $totalResponses, 2) : 0,
                'criteria_stats'    => $criteriaStats,
            ];
        }

        $pdf = Pdf::loadView('admin.reports.summary_pdf', compact('summary'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('summary_report_' . now()->format('Ymd_His') . '.pdf');
    }

    private function streamSummaryCsv($categories, string $filename)
    {
        $rows = [];
        $rows[] = ['Category', 'Type', 'Criteria/Question', 'Average Rating', 'Total Responses', 'Rating Label'];

        foreach ($categories as $category) {
            $totalRating    = 0;
            $totalResponses = 0;

            foreach ($category->criteria ?? [] as $criteria) {
                $responses = $criteria->responses ?? collect();
                $avg   = (float) ($responses->avg('rating') ?? 0);
                $count = $responses->count();

                $rows[] = [
                    (string) ($category->name ?? ''),
                    ucfirst((string) ($category->type ?? '')),
                    (string) ($criteria->question ?? ''),
                    number_format($avg, 2),
                    $count,
                    $this->getRatingLabel($avg),
                ];

                $totalRating    += $avg * $count;
                $totalResponses += $count;
            }

            $overallAvg = $totalResponses > 0 ? $totalRating / $totalResponses : 0.0;
            $rows[] = [
                ($category->name ?? '') . ' (OVERALL)',
                ucfirst((string) ($category->type ?? '')),
                '--- CATEGORY AVERAGE ---',
                number_format($overallAvg, 2),
                optional($category->evaluations)->count() ?? 0,
                $this->getRatingLabel($overallAvg),
            ];
            $rows[] = ['', '', '', '', '', ''];
        }

        return $this->csvResponse($rows, $filename);
    }

    private function streamEvaluationsCsv($evaluations, string $filename)
    {
        $rows = [];
        $rows[] = ['ID', 'Date', 'Category', 'Type', 'Average Rating', 'Comment', 'Academic Year', 'Semester'];

        foreach ($evaluations as $evaluation) {
            $date = $evaluation->created_at ? $evaluation->created_at->format('Y-m-d H:i:s') : '';
            $rows[] = [
                (string) $evaluation->id,
                $date,
                (string) ($evaluation->category->name ?? 'Unknown'),
                ucfirst((string) ($evaluation->category->type ?? 'unknown')),
                number_format((float) ($evaluation->average_rating ?? 0), 2),
                (string) ($evaluation->overall_comment ?? ''),
                (string) ($evaluation->academic_year ?? ''),
                (string) ($evaluation->semester ?? ''),
            ];
        }

        return $this->csvResponse($rows, $filename);
    }

    private function csvResponse(array $rows, string $filename)
    {
        $handle = fopen('php://temp', 'r+');
        fwrite($handle, "\xEF\xBB\xBF");
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-store, no-cache',
        ]);
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
