<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Ensure you import the Role model

class RoleSeeder extends Seeder
{
    /**
     * Seed the roles table.
     *
     * @return void
     */
    public function run(): void
    {
        $roles = [
            'superadmin',
            'admin',
            'management',
            'teachingstaff',
            'nonteachingstaff',
            'parent',
            'student'
        ];
        foreach ($roles as $name) {
            Role::create([
                'name' => $name,
                'status' => 1
            ]);
        }
    }
}
