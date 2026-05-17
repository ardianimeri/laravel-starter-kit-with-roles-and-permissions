<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'create-users']);
        Permission::firstOrCreate(['name' => 'read-users']);
        Permission::firstOrCreate(['name' => 'update-users']);
        Permission::firstOrCreate(['name' => 'delete-users']);
        Permission::firstOrCreate(['name' => 'create-roles']);
        Permission::firstOrCreate(['name' => 'read-roles']);
        Permission::firstOrCreate(['name' => 'update-roles']);
        Permission::firstOrCreate(['name' => 'delete-roles']);
        Permission::firstOrCreate(['name' => 'create-permissions']);
        Permission::firstOrCreate(['name' => 'read-permissions']);
        Permission::firstOrCreate(['name' => 'update-permissions']);
        Permission::firstOrCreate(['name' => 'delete-permissions']);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $user = User::first();
        $user->assignRole($adminRole);

        Role::firstOrCreate(['name' => 'user']);
    }
}
