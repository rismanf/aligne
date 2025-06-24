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
                'parent_id' => 99,
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

        $news = [
            [
                "slug" => "50690",
                "title_slug" => "neutradc-strengthens-education-for-tengger-children-in-bromo-through-technology-donations",
                "title" => "NeutraDC Strengthens Education for Tengger Children in Bromo Through Technology Donations",
                "description" => "NeutraDC Strengthens Education for Tengger Children in Bromo Through Technology Donations",
                "keywords" => "NeutraDC Strengthens Education for Tengger Children in Bromo Through Technology Donations",
                "author" => "Admin",
                "image_original" => "news-covers/9ff1d689-5256-4be0-a0c9-b832ed57d002_original.webp",
                "image_medium" => "news-covers/9ff1d689-5256-4be0-a0c9-b832ed57d002_medium.webp",
                "image_small" => "news-covers/9ff1d689-5256-4be0-a0c9-b832ed57d002_small.webp",
                "category" => null,
                "tags" => null,
                "body" => "<p dir=\"ltr\">&nbsp;</p>\n<p dir=\"ltr\"><img style=\"display: block; margin-left: auto; margin-right: auto;\" src=\"https://lh7-rt.googleusercontent.com/docsz/AD_4nXfsSpI7SxkqNdHon9Rh7mycCkNklDXlk6i8t2cgbMYVOj21s_PDYDxJw_A2Fjai9e0gz1dChUar5ktNUaNtQi1fO9jTGk3RJjcIU9u5y9Cp7rmZYU1-QfApZIPVAOR67TaWSPSH?key=PI0ZMdLdo_xsFjeQDi58uWy-\" width=\"70%\" height=\"70%\"></p>\n<p dir=\"ltr\" style=\"text-align: center;\">A Pasraman teacher...</p>",
                "month" => null,
                "year" => null,
                "is_active" => true,
                "created_by_id" => 1,
                "updated_by_id" => 1,
                "created_at" => "2025-06-16T01:30:50.000Z",
                "updated_at" => "2025-06-16T01:30:50.000Z",
                "deleted_at" => null,
                "published_at" => null,
            ],
            [
                "slug" => "50688",
                "title_slug" => "neutradcs-third-anniversary-strengthening-innovation-through-ai-digital-infrastructure",
                "title" => "NeutraDC's Third Anniversary: Strengthening Innovation Through AI Digital Infrastructure",
                "description" => "NeutraDC's Third Anniversary: Strengthening Innovation Through AI Digital Infrastructure",
                "keywords" => "NeutraDC's Third Anniversary: Strengthening Innovation Through AI Digital Infrastructure",
                "author" => "Admin",
                "image_original" => "news-covers/926fa245-c280-4907-b28a-8752f68b4632_original.webp",
                "image_medium" => "news-covers/926fa245-c280-4907-b28a-8752f68b4632_medium.webp",
                "image_small" => "news-covers/926fa245-c280-4907-b28a-8752f68b4632_small.webp",
                "category" => null,
                "tags" => null,
                "body" => "<p>&nbsp;</p>\n<p dir=\"ltr\"><img style=\"display: block; margin-left: auto; margin-right: auto;\" src=\"https://lh7-rt.googleusercontent.com/docsz/AD_4nXcAuBlW4vJpxu89GT1MEsfAChOu7JDf2LNmfIMSlai-PYH77--DUwGvv4volygZaL6Zl5zIfwiv_mduKg9rdjg171nTzDYRzis1CZbWlcQjDWloRyTgcrzChy9eWjKD7UFZEI6TRA?key=PI0ZMdLdo_xsFjeQDi58uWy-\" width=\"75%\" height=\"75%\"></p>\n<p dir=\"ltr\"><br>Jakarta – Celebrating three years...</p>",
                "month" => null,
                "year" => null,
                "is_active" => true,
                "created_by_id" => 1,
                "updated_by_id" => 1,
                "created_at" => "2025-06-16T21:15:08.000Z",
                "updated_at" => "2025-06-17T01:52:51.000Z",
                "deleted_at" => null,
                "published_at" => "2025-06-17",
            ]
        ];

        foreach ($news as $item) {
            News::create($item);
        }
    }
}
