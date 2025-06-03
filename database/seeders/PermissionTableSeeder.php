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
            'menus-list',
            'menus-create',
            'menus-edit',
            'menus-delete',
            'vcard-list',
            'vcard-create',
            'vcard-edit',
            'vcard-delete',
            'contactus-list',
            'contactus-create',
            'contactus-edit',
            'contactus-delete',
            'participant-list',
            'participant-create',
            'participant-edit',
            'participant-delete',
            'invoice-list',
            'invoice-create',
            'invoice-edit',
            'invoice-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        
    }
}
