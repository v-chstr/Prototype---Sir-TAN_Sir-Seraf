<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'student',
                'display_name' => 'Student',
                'description' => 'Currently enrolled student at SPUP',
            ],
            [
                'name' => 'employee',
                'display_name' => 'Employee',
                'description' => 'Faculty or staff member at SPUP',
            ],
            [
                'name' => 'guest',
                'display_name' => 'Guest',
                'description' => 'External visitor or guest',
            ],
            [
                'name' => 'parent_guardian',
                'display_name' => 'Parent/Guardian',
                'description' => 'Parent or guardian of a student',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
