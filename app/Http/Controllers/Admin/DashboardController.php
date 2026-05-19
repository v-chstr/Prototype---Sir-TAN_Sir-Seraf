<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Evaluation;
use App\Models\EvaluationCategory;
use App\Models\EvaluationResponse;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_users' => User::where('is_admin', false)->count(),
            'total_evaluations' => Evaluation::submitted()->count(),
            'total_categories' => EvaluationCategory::active()->count(),
            'unread_messages' => ContactMessage::unread()->count(),
        ];

        // Get evaluations per category
        $categoryStats = EvaluationCategory::withCount(['evaluations' => function ($query) {
            $query->where('status', 'submitted');
        }])->get();

        // Get recent evaluations
        $recentEvaluations = Evaluation::with(['user', 'category'])
            ->submitted()
            ->latest()
            ->take(10)
            ->get();

        // Get monthly evaluation counts for chart
        $monthlyData = Evaluation::selectRaw("MONTH(created_at) as month, COUNT(*) as count")
            ->whereYear('created_at', date('Y'))
            ->where('status', 'submitted')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Fill in missing months with 0
        $evaluationsByMonth = [];
        for ($i = 1; $i <= 12; $i++) {
            $evaluationsByMonth[$i] = $monthlyData[$i] ?? 0;
        }

        // Get average ratings per category
        $categoryRatings = DB::table('evaluations')
            ->join('evaluation_responses', 'evaluations.id', '=', 'evaluation_responses.evaluation_id')
            ->join('evaluation_categories', 'evaluations.category_id', '=', 'evaluation_categories.id')
            ->where('evaluations.status', 'submitted')
            ->select('evaluation_categories.name', DB::raw('AVG(evaluation_responses.rating) as avg_rating'))
            ->groupBy('evaluation_categories.id', 'evaluation_categories.name')
            ->get();

        // Get user role distribution
        $usersByRole = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.is_admin', false)
            ->select('roles.display_name', DB::raw('COUNT(*) as count'))
            ->groupBy('roles.id', 'roles.display_name')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'categoryStats',
            'recentEvaluations',
            'evaluationsByMonth',
            'categoryRatings',
            'usersByRole'
        ));
    }

    public function evaluations(Request $request)
    {
        $query = Evaluation::with(['user', 'category', 'responses.criteria'])
            ->submitted();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('role')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role_id', $request->role);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $evaluations = $query->latest()->paginate(20);
        $categories = EvaluationCategory::active()->get();
        $roles = Role::whereIn('name', ['student', 'employee', 'guest', 'parent_guardian'])->get();

        return view('admin.evaluations.index', compact('evaluations', 'categories', 'roles'));
    }

    public function showEvaluation($id)
    {
        $evaluation = Evaluation::with(['user', 'category', 'responses.criteria'])->findOrFail($id);
        return view('admin.evaluations.show', compact('evaluation'));
    }

    public function users()
    {
        $users = User::with('role')
            ->where('is_admin', false)
            ->withCount('evaluations')
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function messages()
    {
        $messages = ContactMessage::with('user')->latest()->paginate(20);
        return view('admin.messages.index', compact('messages'));
    }

    public function showMessage($id)
    {
        $message = ContactMessage::findOrFail($id);
        
        if ($message->status === 'unread') {
            $message->update(['status' => 'read']);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function replyMessage(Request $request, $id)
    {
        $request->validate([
            'reply' => ['required', 'string', 'max:2000'],
        ]);

        $message = ContactMessage::findOrFail($id);
        $message->update([
            'admin_reply' => $request->reply,
            'status' => 'replied',
            'replied_at' => now(),
        ]);

        return back()->with('success', 'Reply sent successfully.');
    }

    public function categories()
    {
        $categories = EvaluationCategory::withCount(['criteria', 'evaluations'])->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function chartData()
    {
        // Monthly evaluation trend
        $monthlyTrend = Evaluation::selectRaw("MONTH(created_at) as month, COUNT(*) as count")
            ->whereYear('created_at', date('Y'))
            ->where('status', 'submitted')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Category performance
        $categoryPerformance = DB::table('evaluations')
            ->join('evaluation_responses', 'evaluations.id', '=', 'evaluation_responses.evaluation_id')
            ->join('evaluation_categories', 'evaluations.category_id', '=', 'evaluation_categories.id')
            ->where('evaluations.status', 'submitted')
            ->select('evaluation_categories.name', DB::raw('AVG(evaluation_responses.rating) as avg_rating'))
            ->groupBy('evaluation_categories.id', 'evaluation_categories.name')
            ->get();

        // Rating distribution
        $ratingDistribution = EvaluationResponse::select('rating', DB::raw('COUNT(*) as count'))
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();

        return response()->json([
            'monthlyTrend' => $monthlyTrend,
            'categoryPerformance' => $categoryPerformance,
            'ratingDistribution' => $ratingDistribution,
        ]);
    }
}
