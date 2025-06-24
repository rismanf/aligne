<?php

namespace Database\Seeders;

use App\Models\Questions;
use App\Models\Questions_option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'question' => 'Are you a startup company?',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'single',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => "Topics you're most interested in:",
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'single',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'Will you attend the networking dinner?',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'single',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'Dietary requirements:',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'multiple',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'Topics you’re interested in at AI Collaboration sessions:',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'single',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'If we were to host a Networking Golf Day on 24 June 2025, would you be interested in joining?',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'single',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'Technologies you are actively exploring:',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'multiple',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'Advisory/services you are currently looking for:',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'multiple',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'Logistics Support Needs:',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'single',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
        ];

        foreach ($questions as $question) {
            Questions::firstOrCreate($question);
        }

        $question_options = [
            'Are you a startup company?' => ['Yes', 'No'],

            "Topics you're most interested in:" => [
                'AI Infrastructure (GPU & Baremetal)',
                'Vertical AI Use Cases',
                'AI Playground/Testing',
                'DC Infrastructure',
                'Data Center',
                'Connectivity Equipment',
                'AI Deployment at Scale',
                'AI-Optimized Cooling',
                'Security in AI Infrastructure',
            ],

            'Will you attend the networking dinner?' => ['Yes', 'No', 'Maybe'],

            'Dietary requirements:' => [
                'No dietary requirements',
                'Vegan',
                'Vegetarian',
                'Halal',
                'Kosher',
                'Dairy Free',
                'Gluten Free',
                'Fish/Shellfish Allergy',
                'Nut Allergy',
                'Other',
            ],

            'Topics you’re interested in at AI Collaboration sessions:' => [
                'Cybersecurity & Compliance Tools',
                'AI Data Center Infrastructure & Automation',
                'Sustainability & ESG Monitoring',
                'Hybrid & Cloud Deployment Models for AI Workloads',
                'AI Application Use Cases & Industry Solutions',
                'AI-Driven Interconnectivity',
                'Power Efficiency in AI',
            ],

            'If we were to host a Networking Golf Day on 24 June 2025, would you be interested in joining?' => [
                'Yes',
                'No',
            ],

            'Technologies you are actively exploring:' => [
                'Power',
                'Compute, Storage & Networking',
                'Management & Operations',
                'Cooling',
                'Construction',
                'AI Infrastructure Design',
            ],

            'Advisory/services you are currently looking for:' => [
                'Colocation / Cloud Services',
                'Commissioning & Testing',
                'Construction Services',
                'Data Center Training / Onboarding',
                'Energy Procurement',
                'Environmental & Sustainability',
                'Facilities Management & Maintenance',
                'Financing & Investment',
            ],

            'Logistics Support Needs:' => [
                'I’d like assistance booking hotel',
                'I’d like assistance arranging transport',
                'No, thank you',
            ],
        ];


        foreach ($question_options as $question => $options) {
            foreach ($options as $option) {
                Questions_option::firstOrCreate(
                    [
                        'option' => $option,
                        'question_id' => Questions::where('question', $question)->first()->id,
                        'created_by_id' => 1,
                        'updated_by_id' => 1,
                    ]
                );
            }
        }
    }
}
