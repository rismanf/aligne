<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\News;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu = [
            [
                'name' => 'Home',
                'link' => '/',
                'title' => 'Aligné | Discover Your Strongest Self',
                'description' => 'Whether you come for strength, stillness, or support, Aligné offers more than just fitness',
                'keywords' => 'Main',
                'created_by_id' => 1,
                'is_active' => false,
            ],
            [
                'name' => 'About Us',
                'link' => '/about-us',
                'title' => 'Aligné  | About Us',
                'description' => 'Whether you come for strength, stillness, or support, Aligné offers more than just fitness',
                'keywords' => 'Aligné,About Us,fitness, strength, stillness, support,pilates,yoga',
                'created_by_id' => 1,
            ],
            [
                'name' => 'Classes',
                'link' => '/classes',
                'title' => 'Aligné  | Classes',
                'description' => 'Whether you come for strength, stillness, or support, Aligné offers more than just fitness',
                'keywords' => 'Aligné,About Us,fitness, strength, stillness, support,pilates,yoga',
                'created_by_id' => 1,
            ],
            [
                'name' => 'Membership',
                'link' => '/membership',
                'title' => 'Aligné  | Membership',
                'description' => 'Whether you come for strength, stillness, or support, Aligné offers more than just fitness',
                'keywords' => 'Aligné,About Us,fitness, strength, stillness, support,pilates,yoga',
                'created_by_id' => 1,
            ],
            [
                'name' => 'Contact Us',
                'link' => '/contact-us',
                'title' => 'Contact Us | NeutraDC',
                'description' => 'Get in touch with us for any inquiries or support related to our data center services.',
                'keywords' => 'Contact Us, NeutraDC, Data Center, Indonesia',
                'created_by_id' => 1,
            ],
            
        ];

        foreach ($menu as $item) {
            Menu::create($item);
        }

    }
}
