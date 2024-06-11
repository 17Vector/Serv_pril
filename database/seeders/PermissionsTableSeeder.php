<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
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

        $permissions = [];
        DB::table('permissions')->insert([
            ['name' => 'role-get-story', 'encryption' => Str::uuid()],  //Lab4
            ['name' => 'permission-get-story', 'cipencryptionher' => Str::uuid()],
            ['name' => 'user-get-story', 'encryption' => Str::uuid()],
            ['name' => 'get-logs-collection', 'encryption' => Str::uuid()],
            ['name' => 'restore-log', 'encryption' => Str::uuid()],

            ['name' => 'get-logs-list', 'encryption' => Str::uuid()], //Lab7
            ['name' => 'get-specific-log', 'encryption' => Str::uuid()],
            ['name' => 'delete-log', 'encryption' => Str::uuid()],
        ]);

        DB::table('permissions')->insert($permissions);
    }
}
