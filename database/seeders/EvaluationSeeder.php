<?php

namespace Database\Seeders;

use App\Models\Evaluation;
use App\Models\EvaluationCategory;
use App\Models\EvaluationCriteria;
use App\Models\EvaluationResponse;
use App\Models\User;
use Illuminate\Database\Seeder;

class EvaluationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_admin', false)->get();

        if ($users->isEmpty()) {
            $this->command->warn('No non-admin users found. Run UserSeeder first.');
            return;
        }

        $categories = EvaluationCategory::with('criteria')->where('is_active', true)->get();

        if ($categories->isEmpty()) {
            $this->command->warn('No active categories found. Run EvaluationCategorySeeder first.');
            return;
        }

        $overallComments = [
            'Great service overall. Keep it up!',
            'Needs improvement in some areas but generally okay.',
            'Very satisfied with the experience.',
            'Could use more staff to handle peak hours.',
            'Excellent! Everything was well-organized.',
            'The staff were very accommodating and helpful.',
            'Average experience. Nothing outstanding.',
            'I suggest extending the operating hours.',
            'Good performance. Minor improvements needed.',
            'Outstanding! Very professional and efficient.',
            'Fair service. Some areas need attention.',
            'Highly commendable effort from the team.',
            'Would appreciate faster turnaround times.',
            'The facilities were clean and well-maintained.',
            'Communication could be improved.',
            null,
        ];

        $criteriaComments = [
            'Satisfactory.',
            'Could be better.',
            'Excellent work here.',
            'Needs improvement.',
            'Very good.',
            'No issues at all.',
            'Somewhat lacking.',
            'Impressed with this aspect.',
            null,
            null,
            null,
        ];

        $academicYears = ['2024-2025', '2025-2026'];
        $semesters = ['First Semester', 'Second Semester', 'Summer'];

        foreach ($categories as $category) {
            // 10-16 evaluations per category
            $evalCount = rand(10, 16);
            $shuffledUsers = $users->shuffle();

            for ($i = 0; $i < $evalCount; $i++) {
                $user = $shuffledUsers[$i % $shuffledUsers->count()];

                $evaluation = Evaluation::create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'academic_year' => $academicYears[array_rand($academicYears)],
                    'semester' => $semesters[array_rand($semesters)],
                    'overall_comment' => $overallComments[array_rand($overallComments)],
                    'status' => 'submitted',
                    'created_at' => now()->subDays(rand(0, 180))->subHours(rand(0, 23)),
                ]);

                foreach ($category->criteria as $criteria) {
                    // Weighted random: more 3-5 ratings, fewer 1-2
                    $ratingPool = [1, 2, 2, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5];
                    $rating = $ratingPool[array_rand($ratingPool)];

                    EvaluationResponse::create([
                        'evaluation_id' => $evaluation->id,
                        'criteria_id' => $criteria->id,
                        'rating' => $rating,
                        'comment' => $criteriaComments[array_rand($criteriaComments)],
                    ]);
                }
            }
        }
    }
}
