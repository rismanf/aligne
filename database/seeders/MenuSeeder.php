<?php

namespace Database\Seeders;

use App\Models\Menu;
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
                'title' => 'NeutraDC | Your Digital Ecosystem Partner in Southeast Asia’s Fastest Growing Economy',
                'description' => 'Your Digital Ecosystem Partner in Southeast Asia’s Fastest Growing Economy',
                'keywords' => 'Main',
                'created_by_id' => 1,
                'is_active' => false,
            ],
            [
                'name' => 'NeutraDC Summit',
                'link' => '/neutradc-summit',
                'title' => 'NeutraDC Summit | NeutraDC',
                'description' => 'Our sincerest gratitude to all partners and participants for making NeutraDC Summit 2024 truly spectacular!',
                'keywords' => 'Our sincerest gratitude to all partners and participants for making NeutraDC Summit 2024 truly spectacular!',
                'created_by_id' => 1,
            ],
            [
                'name' => 'Data Center',
                'link' => null,
                'title' => 'NeutraDC Summit | NeutraDC',
                'description' => 'Our sincerest gratitude to all partners and participants for making NeutraDC Summit 2024 truly spectacular!',
                'keywords' => 'Our sincerest gratitude to all partners and participants for making NeutraDC Summit 2024 truly spectacular!',
                'created_by_id' => 1,
            ],
            [
                'name' => 'NeutraDC Jakarta',
                'link' => '/data-center/jakarta-hq',
                'title' => 'Jakarta HQ Data Center | NeutraDC',
                'description' => 'NeutraDC Has Most Reliable Network of Data Center in Indonesia.',
                'keywords' => 'Jakarta HQ, NeutraDC, Data Center, Indonesia, Uptime Institute',
                'parent_id' => 3,
                'created_by_id' => 1,
            ],
            [
                'name' => 'NeutraDC Batam',
                'link' => '/data-center/batam',
                'title' => 'Batam Data Center | NeutraDC',
                'description' => 'NeutraDC Has Most Reliable Network of Data Center in Indonesia.',
                'keywords' => 'Batam, NeutraDC, Data Center, Indonesia, Uptime Institute',
                'parent_id' => 3,
                'created_by_id' => 1,
            ],
            [
                'name' => 'NeutraDC Singapore',
                'link' => '/data-center/singapore',
                'title' => 'Singapore Data Center | NeutraDC',
                'description' => 'NeutraDC Has Most Reliable Network of Data Center in Indonesia.',
                'keywords' => 'Singapore, NeutraDC, Data Center, Indonesia, Uptime Institute',
                'parent_id' => 3,
                'created_by_id' => 1,
            ],
            [
                'name' => 'Services',
                'link' => '/services',
                'title' => 'NeutraDC Services | NeutraDC',
                'description' => 'Our Data Center Are Managed by a Highly-Experienced Data Center Team.',
                'keywords' => 'NeutraDC, Services, Colocation, Cloud Computing, Managed Services',
                'created_by_id' => 1,
            ],
            [
                'name' => 'About Us',
                'link' => '/about-us',
                'title' => 'About Us | NeutraDC',
                'description' => 'A Digital Ecosystem Hub for Unlimited Access to Indonesia’s Digital Economy.',
                'keywords' => 'NeutraDC, About Us, Data Center, Colocation, Cloud Computing, Managed Services',
                'created_by_id' => 1,
            ],
            [
                'name' => 'News',
                'link' => '/news',
                'title' => 'News | NeutraDC',
                'description' => 'A Digital Ecosystem Hub for Unlimited Access to Indonesia’s Digital Economy.',
                'keywords' => 'NeutraDC, News, Data Center, Colocation, Cloud Computing, Managed Services',
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
            [
                'name' => 'Two Hands Hub',
                'link' => '/two-hands-hub',
                'title' => 'Two Hands Hub | NeutraDC',
                'description' => 'Two Hands Hub | NeutraDC',
                'keywords' => 'Two Hands Hub | NeutraDC',
                'created_by_id' => 1,
            ],
        ];

        foreach ($menu as $item) {
            Menu::create($item);
        }
    }
}
