<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = ['user', 'role', 'permission', 'user_and_role', 'role_and_permission'];
        $actions = ['get-list', 'read', 'create', 'update', 'delete', 'restore'];

        foreach ($tables as $table) {
            foreach ($actions as $action) {
                Permission::create([
                    'name' => $table . '-' . $action, 
                    'encryption' => Str::uuid()
                ]);
            }
        }
    }
}
