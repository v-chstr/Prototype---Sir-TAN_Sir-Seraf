<?php

namespace Database\Seeders;

use App\Models\EvaluationCategory;
use App\Models\EvaluationCriteria;
use Illuminate\Database\Seeder;

class EvaluationCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Standards Categories
        $standards = [
            [
                'name' => 'Administration Leaders',
                'type' => 'standard',
                'description' => 'Evaluate the leadership and administrative performance of university leaders',
                'icon' => 'bi-person-badge',
                'criteria' => [
                    'The administration demonstrates effective leadership skills',
                    'Communication from administration is clear and timely',
                    'The administration is accessible and approachable',
                    'Decisions made by administration are fair and transparent',
                    'The administration actively supports student welfare',
                    'The administration promotes a positive academic environment',
                ],
            ],
            [
                'name' => 'Learning Environment',
                'type' => 'standard',
                'description' => 'Evaluate the quality of the learning environment and academic support',
                'icon' => 'bi-book',
                'criteria' => [
                    'Classrooms are conducive to learning',
                    'Teaching methods are effective and engaging',
                    'Academic resources and materials are adequate',
                    'The library provides sufficient resources',
                    'Technology integration in learning is effective',
                    'Academic support services are readily available',
                ],
            ],
            [
                'name' => 'Facilities',
                'type' => 'standard',
                'description' => 'Evaluate the university facilities and infrastructure',
                'icon' => 'bi-building',
                'criteria' => [
                    'Buildings and facilities are well-maintained',
                    'Restrooms are clean and properly maintained',
                    'Campus security measures are adequate',
                    'Parking facilities are sufficient',
                    'Sports and recreational facilities are available',
                    'Accessibility features for persons with disabilities are adequate',
                ],
            ],
        ];

        // Office Categories
        $offices = [
            [
                'name' => 'Healthcare Services',
                'type' => 'office',
                'description' => 'Evaluate the university health services and medical facilities',
                'icon' => 'bi-hospital',
                'criteria' => [
                    'Medical staff are professional and competent',
                    'Response time for medical emergencies is adequate',
                    'Health facilities and equipment are sufficient',
                    'Health awareness programs are effective',
                    'Confidentiality of medical records is maintained',
                ],
            ],
            [
                'name' => 'ICT Services',
                'type' => 'office',
                'description' => 'Evaluate Information and Communication Technology services',
                'icon' => 'bi-pc-display',
                'criteria' => [
                    'Internet connectivity is reliable and fast',
                    'Computer laboratories are well-equipped',
                    'Technical support response time is satisfactory',
                    'Online systems (portal, LMS) are user-friendly',
                    'ICT staff are helpful and knowledgeable',
                ],
            ],
            [
                'name' => 'Canteen Services',
                'type' => 'office',
                'description' => 'Evaluate the canteen and food services',
                'icon' => 'bi-cup-hot',
                'criteria' => [
                    'Food quality is satisfactory',
                    'Food prices are reasonable',
                    'Canteen cleanliness is maintained',
                    'Food variety is adequate',
                    'Service speed is satisfactory',
                    'Staff are courteous and helpful',
                ],
            ],
            [
                'name' => 'Registrar Office',
                'type' => 'office',
                'description' => 'Evaluate the Registrar Office services',
                'icon' => 'bi-file-earmark-text',
                'criteria' => [
                    'Processing of documents is timely',
                    'Staff are courteous and professional',
                    'Information provided is accurate and clear',
                    'Queue management is efficient',
                    'Online services are accessible and functional',
                ],
            ],
            [
                'name' => 'Office of Student Affairs (OSA)',
                'type' => 'office',
                'description' => 'Evaluate the Office of Student Affairs services',
                'icon' => 'bi-people',
                'criteria' => [
                    'Student activities and programs are well-organized',
                    'Counseling services are accessible',
                    'Student concerns are addressed promptly',
                    'Communication about events and activities is effective',
                    'Support for student organizations is adequate',
                ],
            ],
        ];

        foreach (array_merge($standards, $offices) as $categoryData) {
            $criteria = $categoryData['criteria'];
            unset($categoryData['criteria']);

            $category = EvaluationCategory::create($categoryData);

            foreach ($criteria as $order => $question) {
                EvaluationCriteria::create([
                    'category_id' => $category->id,
                    'question' => $question,
                    'order' => $order + 1,
                ]);
            }
        }
    }
}
