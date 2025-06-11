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
        $user = User::create([
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

        $role = Role::create(['name' => 'Admin']);


        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);
        
        $user1 = Role::create(['name' => 'User']);
        $user1->syncPermissions($permissions);

        $user->assignRole('Admin');
    }
}
