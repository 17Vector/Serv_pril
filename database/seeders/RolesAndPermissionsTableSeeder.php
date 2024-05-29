<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin') -> first();
        $userRole = Role::where('name', 'User') -> first();
        $guestRole = Role::where('name', 'Guest') -> first();

        $adminPermissions = Permission::all();
        $adminRole -> permissions() -> sync($adminPermissions->pluck('id')->toArray());

        $userPermissions = Permission::whereIn('name', ['user-get-list', 'user-read', 'user-update', 'user_and_role-read'])->get();
        $userRole -> permissions() -> sync($userPermissions->pluck('id')->toArray());

        $guestPermissions = Permission::whereIn('name', ['user-get-list'])->get();
        $guestRole -> permissions() -> sync($guestPermissions->pluck('id')->toArray());
    }
}
