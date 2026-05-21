<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SPUP Summary Report</title>
    <style>
        @page { margin: 30px 30px 40px 30px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
        h1, h2, h3 { margin: 0 0 6px 0; color: #0d5c36; }
        h1 { font-size: 18px; }
        h2 { font-size: 13px; margin-top: 16px; margin-bottom: 6px; }
        .header { border-bottom: 2px solid #198754; padding-bottom: 8px; margin-bottom: 12px; }
        .muted { color: #666; font-size: 10px; }
        .meta { font-size: 10px; color: #555; margin-bottom: 14px; }
        .category-block { margin-bottom: 20px; page-break-inside: avoid; }
        .category-header { background: #198754; color: #fff; padding: 6px 10px; border-radius: 3px 3px 0 0; }
        .category-header .badge { display: inline-block; background: rgba(255,255,255,0.25); padding: 1px 7px; border-radius: 3px; font-size: 9px; margin-right: 6px; }
        .category-header .overall { float: right; font-size: 11px; font-weight: bold; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th { background: #e8f5e9; color: #0d5c36; padding: 5px 6px; text-align: left; font-size: 10px; border-bottom: 1px solid #c8e6c9; }
        table.data td { padding: 5px 6px; border-bottom: 1px solid #eee; font-size: 10px; vertical-align: middle; }
        table.data tr:last-child td { border-bottom: none; }
        .badge-label { display: inline-block; padding: 2px 6px; border-radius: 3px; color: #fff; font-size: 9px; font-weight: bold; }
        .b-success { background: #198754; }
        .b-primary { background: #0d6efd; }
        .b-warning { background: #ffc107; color: #222; }
        .b-secondary { background: #6c757d; }
        .b-danger  { background: #dc3545; }
        .footer { position: fixed; bottom: 10px; left: 30px; right: 30px; font-size: 9px; color: #888; text-align: center; border-top: 1px solid #ddd; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="footer">
        SPUP Integrated Evaluation and Feedback System &mdash; Confidential &mdash; Generated {{ now()->format('M d, Y h:i A') }}
    </div>

    <div class="header">
        <h1>SPUP Integrated Evaluation and Feedback System</h1>
        <div class="muted">St. Paul University Philippines &mdash; Comprehensive Summary Report</div>
    </div>

    <div class="meta">
        <strong>Generated:</strong> {{ now()->format('F d, Y h:i A') }} &nbsp;&nbsp;
        <strong>Total Categories:</strong> {{ count($summary) }}
    </div>

    @foreach($summary as $item)
    <div class="category-block">
        <div class="category-header">
            <span class="badge">{{ ucfirst($item['type']) }}</span>
            {{ $item['category'] }}
            <span class="overall">Overall: {{ $item['overall_avg'] }}/5 &nbsp;|&nbsp; {{ $item['total_evaluations'] }} Evaluations</span>
        </div>
        <table class="data">
            <thead>
                <tr>
                    <th style="width:52%">Criteria / Question</th>
                    <th class="text-center" style="width:15%">Avg Rating</th>
                    <th class="text-center" style="width:15%">Responses</th>
                    <th style="width:18%">Label</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item['criteria_stats'] as $stat)
                <tr>
                    <td>{{ $stat['question'] }}</td>
                    <td style="text-align:center">{{ $stat['avg_rating'] }}</td>
                    <td style="text-align:center">{{ $stat['response_count'] }}</td>
                    <td>
                        @if($stat['avg_rating'] >= 4.5)
                            <span class="badge-label b-success">Excellent</span>
                        @elseif($stat['avg_rating'] >= 3.5)
                            <span class="badge-label b-primary">Very Good</span>
                        @elseif($stat['avg_rating'] >= 2.5)
                            <span class="badge-label b-warning">Good</span>
                        @elseif($stat['avg_rating'] >= 1.5)
                            <span class="badge-label b-secondary">Fair</span>
                        @else
                            <span class="badge-label b-danger">Poor</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
</body>
</html>
