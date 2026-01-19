<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EvaluationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $evaluations;

    public function __construct($evaluations)
    {
        $this->evaluations = $evaluations;
    }

    public function collection()
    {
        return $this->evaluations;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Evaluator',
            'Email',
            'Category',
            'Type',
            'Average Rating',
            'Overall Comment',
            'Academic Year',
            'Semester',
        ];
    }

    public function map($evaluation): array
    {
        return [
            $evaluation->id,
            $evaluation->created_at->format('Y-m-d H:i:s'),
            $evaluation->user->name ?? 'Anonymous',
            $evaluation->user->email ?? 'N/A',
            $evaluation->category->name ?? 'Unknown',
            ucfirst($evaluation->category->type ?? 'unknown'),
            number_format($evaluation->average_rating, 2),
            $evaluation->overall_comment ?? 'No comment',
            $evaluation->academic_year,
            $evaluation->semester,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
