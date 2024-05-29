<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Admin', 'encryption' => Str::uuid()]);
        Role::create(['name' => 'User', 'encryption' => Str::uuid()]);
        Role::create(['name' => 'Guest', 'encryption' => Str::uuid()]);
    }
}