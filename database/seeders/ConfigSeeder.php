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

        $role_corsec = Role::create(['name' => 'Corsec']);
        $role_corsec->givePermissionTo(
            [
                'news-list',
                'news-create',
                'news-edit',
                'news-delete',
                'menu-list',
                'menu-create',
                'menu-edit',
                'menu-delete',
                'vcard-list',
                'vcard-create',
                'vcard-edit',
                'vcard-delete',
                'contactus-list',
                'contactus-create',
                'contactus-edit',
                'contactus-delete',
                'question-list',
                'question-create',
                'question-edit',
                'question-delete',
            ]
        );

        $role_sales = Role::create(['name' => 'Sales']);
        $role_sales->givePermissionTo(
            [
                'participant-list',
                'participant-create',
                'participant-edit',
                'participant-delete',
                'sponsor-list',
                'sponsor-create',
                'sponsor-edit',
                'sponsor-delete',
            ]
        );
    }
}
