<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin = User::create([
            'name' => 'Risman Firmansyah',
            'phone' => '0812345678',
            'title' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password')
        ]);

        $role_admin = Role::create(['name' => 'Admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $admin->assignRole('Admin');
        $role_admin->syncPermissions($permissions);

       $role_user = Role::create(['name' => 'User']);
    }
}
