<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $teacherRole = Role::create(['name' => 'teacher']);
        $parentRole = Role::create(['name' => 'parent']);

        // Create permissions for teachers
        $permissions = [
            'create_grade',
            'edit_grade',
            'delete_grade',
            'view_grades',
            'create_map',
            'edit_map',
            'delete_map',
            'view_maps',
            'create_stage',
            'edit_stage',
            'delete_stage',
            'view_stages',
            'create_lesson',
            'edit_lesson',
            'delete_lesson',
            'view_lessons',
            'create_game_mode',
            'edit_game_mode',
            'delete_game_mode',
            'view_game_modes',
            'create_question',
            'edit_question',
            'delete_question',
            'view_questions',
            'view_dashboard',
            'view_leaderboard',
            'view_analytics'
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign all permissions to teacher role
        $teacherRole->givePermissionTo($permissions);

        // Assign basic permissions to parent role
        $parentRole->givePermissionTo([
            'view_grades',
            'view_maps',
            'view_stages',
            'view_lessons',
            'view_game_modes',
            'view_questions'
        ]);

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info('Teacher role created with all permissions.');
        $this->command->info('Parent role created with view permissions.');
    }
}
