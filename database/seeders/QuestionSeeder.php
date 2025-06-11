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
                'question' => 'What type of advisory firm do you work for?',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'multiple',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'What type of investment company do you work for?',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'multiple',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'Which event are you primarily interested in attending? We will provide you event updates based on your selection.',
                'type_user' => 1,
                'event_id' => 1,
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'We hold technology discovery sessions where you’ll hear rapid-fire pitches on a tech area of interest to you.',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'multiple',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'Which closed-door Lunch Briefings most interest you? Please select two.',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'multiple',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'Please confirm if you have any dietary requirements',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'multiple',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'If we were to run a Networking Golf Day on 16 June, ahead of the Icebreaker Networking Party, would you be interested in playing?',
                'type_user' => 7,
                'event_id' => 1,
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'Which technologies are you actively exploring?',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'multiple',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'Which types of advisory/services are you currently looking for?',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'multiple',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'question' => 'Finally, what types of projects are you currently working on?',
                'type_user' => 1,
                'event_id' => 1,
                'question_type' => 'multiple',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
        ];


        foreach ($questions as $question) {
            Questions::firstOrCreate($question);
        }

        $question_options = [
            'What type of advisory firm do you work for?' => [
                'Consulting Engineers',
                'Building Contractors',
                'Architects',
                'Real Estate Brokers',
                'Legal Advisory',
                'Facilities Management',
                'Investment/Real Estate Development',
                'Business/Strategy Consultancy',
                'Energy Advisory',
                'Other',

            ],
            'What type of investment company do you work for?' => [
                'Private Equity Firm',
                'REIT',
                'Commercial Real Estate Firm',
                'Asset Management Firm',
                'Investment Bank',
                'Brokerage Firm',
                'Law Firm',
                'Consulting Firm',
                'Other',
            ],
            'Which event are you primarily interested in attending? We will provide you event updates based on your selection.' => [
                'Neutradc Summit',
                'Neutradc Summit - Jakarta',
                'Neutradc Summit - Batam',
                'Neutradc Summit - Singapore',
            ],
            'We hold technology discovery sessions where you’ll hear rapid-fire pitches on a tech area of interest to you.' => [
                'Management & Operations',
                'Data Center Cooling',
                'Power & Sustainability',
                'Data Center Construction',
                'Connectivity',
                'Compute, Storage & Networking',
            ],
            'Which closed-door Lunch Briefings most interest you? Please select two.' => [
                'Energy & Sustainability Lunch',
                'Data Center Cooling Lunch',
                'Mission Critical Power Lunch',
                'Management & Operations Lunch',
                'Data Center Construction Lunch',
                'Edge Computing Lunch',
                'Capacity Planning Lunch',
                'Telecoms & Connectivity Lunch',
            ],
            'Please confirm if you have any dietary requirements' => [
                'I have no dietary requirements',
                'Vegan',
                'Vegetarian',
                'Halal',
                'Kosher',
                'Dairy Free',
                'Gluten Free',
                'Fish/Shell Fish Allergy',
                'Nut Allergy',
                'Other',
            ],
            'If we were to run a Networking Golf Day on 16 June, ahead of the Icebreaker Networking Party, would you be interested in playing?' => [
                'Yes',
                'No',
            ],
            'Which technologies are you actively exploring?' => [
                'Power',
                'Compute, Storage & Networking',
                'Management & Operations',
                'Cooling',
                'Construction',
                'None of the above',
            ],
            'Which types of advisory/services are you currently looking for?' => [
                'Colocation/Cloud Services',
                'Commissioning & Testing',
                'Construction Services',
                'Data Center Training/Onboarding',
                'Energy Procurement',
                'Environmental & Sustainability',
                'Facilities Management & Maintenance',
                'Financing & Investment',
                'MEP Design Engineering',
                'Project Management',
                'Real Estate/Property Development',
                'Recycling & Data Center Decommissioning',
                'Supply Chain',
                'Telecoms & Interconnection',
                'None of the above',
            ],
            'Finally, what types of projects are you currently working on?' => [
                'Deploying edge data centers',
                'Designing/building a new data center',
                'Modernizing an existing data center',
                'Outsourcing or colocating capacity requirements',
                'Upgrading data center technology',
                'None of the above',
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
