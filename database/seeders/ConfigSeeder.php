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
            'first_name' => 'Risman',
            'last_name' => 'Firmansyah',
            'ad_name' => 'admin',
            'phone' => '0812345678',
            'nik' => '2205022',
            'title' => 'Administrator2',
            'department' => 'Enterprise IT System & Automation',
            'email' => 'admin@neutradc.com',
            'type_user' => 'Internal',
            'country' => 'Indonesia',
            'company' => 'NeutraDC',
            'password' => bcrypt('password')
        ]);

        $role_admin = Role::create(['name' => 'Admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $admin->assignRole('Admin');
        $role_admin->syncPermissions($permissions);

        $role_user = Role::create(['name' => 'User']);
        $role_user->givePermissionTo(
            [
                'participant-list',
                'participant-create',
                'participant-edit',
                'participant-delete',
                'invoice-list',
                'invoice-confirm',
                'invoice-cancel',
            ]
        );

        $role_finance = Role::create(['name' => 'Finance']);
        $role_finance->givePermissionTo(
            [
                'invoice-list',
                'invoice-approve',
                'invoice-reject',
            ]
        );
    }
}
