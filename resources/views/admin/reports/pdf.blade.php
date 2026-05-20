<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SPUP Evaluation Report</title>
    <style>
        @page { margin: 30px 30px 40px 30px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
        h1, h2, h3, h4 { margin: 0 0 6px 0; color: #0d5c36; }
        h1 { font-size: 18px; }
        h2 { font-size: 14px; margin-top: 16px; }
        h3 { font-size: 12px; }
        .header { border-bottom: 2px solid #198754; padding-bottom: 8px; margin-bottom: 12px; }
        .muted { color: #666; font-size: 10px; }
        .meta-table { width: 100%; margin-bottom: 12px; }
        .meta-table td { padding: 3px 6px; font-size: 10px; }
        .stats { width: 100%; margin: 10px 0 14px 0; border-collapse: collapse; }
        .stats td { width: 33.33%; text-align: center; padding: 10px; border: 1px solid #e0e0e0; }
        .stats .num { font-size: 18px; font-weight: bold; color: #198754; }
        .stats .lbl { font-size: 9px; color: #666; text-transform: uppercase; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        table.data th { background: #198754; color: #fff; padding: 6px; text-align: left; font-size: 10px; }
        table.data td { padding: 5px 6px; border-bottom: 1px solid #eee; font-size: 10px; vertical-align: top; }
        table.data tr:nth-child(even) td { background: #fafafa; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; color: #fff; font-size: 9px; font-weight: bold; }
        .b-success { background: #198754; }
        .b-warning { background: #ffc107; color: #222; }
        .b-danger  { background: #dc3545; }
        .footer { position: fixed; bottom: 10px; left: 30px; right: 30px; font-size: 9px; color: #888; text-align: center; border-top: 1px solid #ddd; padding-top: 5px; }
        .pagenum:before { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <h1>SPUP Integrated Evaluation and Feedback System</h1>
        <div class="muted">St. Paul University Philippines &mdash; Detailed Evaluation Report</div>
    </div>

    <table class="meta-table">
        <tr>
            <td><strong>Report Type:</strong> {{ ucfirst($request->report_type ?? 'Detailed') }}</td>
            <td><strong>Category:</strong>
                @if(!empty($request->category_id))
                    {{ optional(\App\Models\EvaluationCategory::find($request->category_id))->name ?? 'All' }}
                @else
                    All Categories
                @endif
            </td>
            <td><strong>Generated:</strong> {{ now()->format('M d, Y h:i A') }}</td>
        </tr>
        <tr>
            <td><strong>Date From:</strong> {{ $request->date_from ?? 'N/A' }}</td>
            <td><strong>Date To:</strong> {{ $request->date_to ?? 'N/A' }}</td>
            <td><strong>Total Records:</strong> {{ $evaluations->count() }}</td>
        </tr>
    </table>

    <h2>Summary Statistics</h2>
    <table class="stats">
        <tr>
            <td>
                <div class="num">{{ $stats['total_evaluations'] }}</div>
                <div class="lbl">Total Evaluations</div>
            </td>
            <td>
                <div class="num">{{ $stats['total_responses'] }}</div>
                <div class="lbl">Total Responses</div>
            </td>
            <td>
                <div class="num">{{ $stats['average_rating'] }}/5</div>
                <div class="lbl">Overall Avg Rating</div>
            </td>
        </tr>
    </table>

    @if(!empty($categoryStats) && $categoryStats->count() > 0)
    <h2>Evaluations by Category</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Category</th>
                <th style="width:120px;text-align:right;">Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categoryStats as $stat)
                <tr>
                    <td>{{ $stat['name'] }}</td>
                    <td style="text-align:right;">{{ $stat['count'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(!empty($criteriaStats) && count($criteriaStats) > 0)
    <h2>Criteria Performance</h2>
    <table class="data">
        <thead>
            <tr>
                <th style="width:25%;">Category</th>
                <th>Question</th>
                <th style="width:70px;text-align:center;">Avg</th>
                <th style="width:80px;text-align:center;">Responses</th>
            </tr>
        </thead>
        <tbody>
            @foreach($criteriaStats as $cs)
                @php
                    $avg = (float) ($cs['avg_rating'] ?? 0);
                    $cls = $avg >= 4 ? 'b-success' : ($avg >= 3 ? 'b-warning' : 'b-danger');
                @endphp
                <tr>
                    <td>{{ $cs['category'] ?? '' }}</td>
                    <td>{{ $cs['question'] ?? '' }}</td>
                    <td style="text-align:center;"><span class="badge {{ $cls }}">{{ number_format($avg, 2) }}</span></td>
                    <td style="text-align:center;">{{ $cs['total_responses'] ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <h2>Detailed Evaluations</h2>
    @if($evaluations->count() === 0)
        <p class="muted">No evaluations found for the selected filters.</p>
    @else
    <table class="data">
        <thead>
            <tr>
                <th style="width:90px;">Date</th>
                <th style="width:120px;">Respondent</th>
                <th>Category</th>
                <th style="width:65px;text-align:center;">Rating</th>
                <th>Comment</th>
            </tr>
        </thead>
        <tbody>
            @foreach($evaluations as $evaluation)
                @php
                    $rating = (float) ($evaluation->average_rating ?? 0);
                    $cls = $rating >= 4 ? 'b-success' : ($rating >= 3 ? 'b-warning' : 'b-danger');
                @endphp
                <tr>
                    <td>{{ $evaluation->created_at?->format('M d, Y') }}</td>
                    <td>{{ \App\Helpers\AnonymizeHelper::anonymizeUser($evaluation->user->id ?? $evaluation->id) }}</td>
                    <td>{{ $evaluation->category->name ?? 'Unknown' }}</td>
                    <td style="text-align:center;"><span class="badge {{ $cls }}">{{ number_format($rating, 1) }}</span></td>
                    <td>{{ $evaluation->overall_comment ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        SPUP Evaluation System &mdash; Confidential Report &mdash; Page <span class="pagenum"></span>
    </div>
</body>
</html>
