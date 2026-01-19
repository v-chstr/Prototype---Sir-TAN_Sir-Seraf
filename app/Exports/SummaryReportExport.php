<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SummaryReportExport implements FromArray, WithHeadings, WithStyles, WithTitle
{
    protected $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->categories as $category) {
            $totalRating = 0;
            $totalResponses = 0;

            foreach ($category->criteria as $criteria) {
                $responses = $criteria->responses;
                $avgRating = $responses->avg('rating') ?? 0;
                $count = $responses->count();

                $data[] = [
                    $category->name,
                    ucfirst($category->type),
                    $criteria->question,
                    number_format($avgRating, 2),
                    $count,
                    $this->getRatingLabel($avgRating),
                ];

                $totalRating += $avgRating * $count;
                $totalResponses += $count;
            }

            // Add category summary row
            $overallAvg = $totalResponses > 0 ? $totalRating / $totalResponses : 0;
            $data[] = [
                $category->name . ' (OVERALL)',
                ucfirst($category->type),
                '--- CATEGORY AVERAGE ---',
                number_format($overallAvg, 2),
                $category->evaluations->count(),
                $this->getRatingLabel($overallAvg),
            ];

            // Add empty row for separation
            $data[] = ['', '', '', '', '', ''];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Category',
            'Type',
            'Criteria/Question',
            'Average Rating',
            'Total Responses',
            'Rating Label',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Summary Report';
    }

    private function getRatingLabel($rating)
    {
        if ($rating >= 4.5) return 'Excellent';
        if ($rating >= 3.5) return 'Very Good';
        if ($rating >= 2.5) return 'Good';
        if ($rating >= 1.5) return 'Fair';
        return 'Poor';
    }
}
