<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([            
            PermissionTableSeeder::class,
            ConfigSeeder::class,
            MasterSeeder::class,
            MenuSeeder::class,
            FitnessAppSeeder::class,
            // QuestionSeeder::class,
        ]);
    }
}
