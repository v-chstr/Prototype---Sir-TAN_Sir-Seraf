<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::all()->keyBy('name');

        // Dev admin account
        User::firstOrCreate(
            ['email' => 'chester.tambis.admin@gmail.com'],
            [
                'name' => 'Chester Tambis',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Dev user account
        User::firstOrCreate(
            ['email' => 'chester.tambis.user@gmail.com'],
            [
                'name' => 'Chester Tambis',
                'password' => Hash::make('password'),
                'role_id' => $roles['student']->id,
                'student_id' => 'STU-2024-0001',
                'department' => 'School of Information Technology and Engineering',
                'section' => 'SITE-A',
                'year_level' => '3rd Year',
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        $departments = [
            'School of Health and Allied Health Sciences',
            'School of Business and Hospitality Management',
            'School of Information Technology and Engineering',
            'School of Arts, Science, and Teacher Education',
            'School of Medicine',
        ];

        $sectionsByDepartment = [
            'School of Health and Allied Health Sciences' => ['SHAHS-A', 'SHAHS-B', 'SHAHS-C'],
            'School of Business and Hospitality Management' => ['SBHM-A', 'SBHM-B', 'SBHM-C'],
            'School of Information Technology and Engineering' => ['SITE-A', 'SITE-B', 'SITE-C'],
            'School of Arts, Science, and Teacher Education' => ['SASTE-A', 'SASTE-B', 'SASTE-C'],
            'School of Medicine' => ['SM-A', 'SM-B'],
        ];

        $yearLevelsByDepartment = [
            'School of Health and Allied Health Sciences' => ['1st Year', '2nd Year', '3rd Year', '4th Year'],
            'School of Business and Hospitality Management' => ['1st Year', '2nd Year', '3rd Year', '4th Year'],
            'School of Information Technology and Engineering' => ['1st Year', '2nd Year', '3rd Year', '4th Year'],
            'School of Arts, Science, and Teacher Education' => ['1st Year', '2nd Year', '3rd Year', '4th Year'],
            'School of Medicine' => ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'],
        ];

        $comments = [
            'Great service overall.',
            'Needs improvement in responsiveness.',
            'Very helpful and professional.',
            'Could be better, but satisfactory.',
            'Excellent experience.',
            'The staff were very accommodating.',
            'Average service quality.',
            'Highly recommend improvements.',
            'Good but room for improvement.',
            'Outstanding performance.',
            null,
        ];

        // Generate 100 regular users across roles
        $userCount = 0;
        $roleDistribution = [
            'student' => 50,
            'employee' => 25,
            'guest' => 15,
            'parent_guardian' => 10,
        ];

        foreach ($roleDistribution as $roleName => $count) {
            $role = $roles[$roleName];

            for ($i = 1; $i <= $count; $i++) {
                $userCount++;
                $dept = $departments[array_rand($departments)];

                $yearLevels = $yearLevelsByDepartment[$dept];
                $sections = $sectionsByDepartment[$dept];

                $userData = [
                    'name' => fake()->name(),
                    'email' => fake()->unique()->safeEmail(),
                    'password' => Hash::make('password'),
                    'role_id' => $role->id,
                    'department' => $dept,
                    'section' => $sections[array_rand($sections)],
                    'year_level' => $yearLevels[array_rand($yearLevels)],
                    'is_admin' => false,
                    'email_verified_at' => now(),
                    'phone' => fake()->phoneNumber(),
                ];

                if ($roleName === 'student') {
                    $userData['student_id'] = 'STU-2024-' . str_pad($userCount, 4, '0', STR_PAD_LEFT);
                } elseif ($roleName === 'employee') {
                    $userData['employee_id'] = 'EMP-' . str_pad($userCount, 4, '0', STR_PAD_LEFT);
                }

                User::create($userData);
            }
        }
    }
}
