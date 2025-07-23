<?php

namespace Database\Seeders;

use App\Models\Aplication;
use App\Models\Devision;
use App\Models\Status_config;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'news-list',
            'news-create',
            'news-edit',
            'news-delete',
            'menu-list',
            'menu-create',
            'menu-edit',
            'menu-delete',
            'contactus-list',
            'contactus-create',
            'contactus-edit',
            'contactus-delete',
            'invoice-list',
            'invoice-create',
            'invoice-edit',
            'invoice-delete',
            'invoice-confirm',
            'invoice-cancel',
            'invoice-approve',
            'invoice-reject',
            'email-list',
            'email-create',
            'email-edit',
            'email-delete',
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',
            'trainer-list',
            'trainer-create',
            'trainer-edit',
            'trainer-delete',
            'class-list',
            'class-create',
            'class-edit',
            'class-delete',
            'schedule-list',
            'schedule-create',
            'schedule-edit',
            'schedule-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
