<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvaluationCategory;
use App\Models\EvaluationCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = EvaluationCategory::withCount(['criteria', 'evaluations'])->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:standard,office'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:100'],
            'criteria' => ['required', 'array', 'min:1'],
            'criteria.*' => ['required', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request) {
            $category = EvaluationCategory::create([
                'name' => $request->name,
                'type' => $request->type,
                'description' => $request->description,
                'icon' => $request->icon ?: ($request->type === 'office' ? 'bi-building' : 'bi-star'),
                'is_active' => true,
            ]);

            foreach ($request->criteria as $order => $question) {
                $question = trim($question);
                if ($question !== '') {
                    EvaluationCriteria::create([
                        'category_id' => $category->id,
                        'question' => $question,
                        'order' => $order + 1,
                        'is_active' => true,
                    ]);
                }
            }
        });

        return redirect()->route('admin.categories')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = EvaluationCategory::with(['criteria' => function ($q) {
            $q->orderBy('order');
        }])->findOrFail($id);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = EvaluationCategory::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:standard,office'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:100'],
            'criteria' => ['required', 'array', 'min:1'],
            'criteria.*' => ['required', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $category) {
            $category->update([
                'name' => $request->name,
                'type' => $request->type,
                'description' => $request->description,
                'icon' => $request->icon ?: $category->icon,
            ]);

            // Sync criteria: deactivate removed, update existing, add new
            $existingIds = $category->criteria()->pluck('id')->toArray();
            $keepIds = [];

            foreach ($request->criteria as $order => $question) {
                $question = trim($question);
                if ($question === '') {
                    continue;
                }

                // Check if it matches an existing criteria by order
                $existingCriteria = $category->criteria()->where('order', $order + 1)->first();

                if ($existingCriteria) {
                    $existingCriteria->update([
                        'question' => $question,
                        'order' => $order + 1,
                    ]);
                    $keepIds[] = $existingCriteria->id;
                } else {
                    $new = EvaluationCriteria::create([
                        'category_id' => $category->id,
                        'question' => $question,
                        'order' => $order + 1,
                        'is_active' => true,
                    ]);
                    $keepIds[] = $new->id;
                }
            }

            // Deactivate criteria that were removed (don't delete — preserves historical data)
            $category->criteria()->whereNotIn('id', $keepIds)->update(['is_active' => false]);
        });

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully.');
    }

    public function toggleActive($id)
    {
        $category = EvaluationCategory::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Category \"{$category->name}\" {$status}.");
    }
}
