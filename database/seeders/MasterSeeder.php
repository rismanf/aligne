<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\master_data;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $request_status = [
            'Internal',
            'End-customer',
            'General Admission',
            'Patner/Sponsor',
        ];

        foreach ($request_status as $v => $stat) {
            master_data::create([
                'type' => 'Type User',
                'code' => $v + 1,
                'name' => $stat,
            ]);
        }

        $request_status = [
            'Unpaid',
            'Waiting for Payment',
            'Waiting for Payment Confirmation',
            'Paid',
        ];

        foreach ($request_status as $v => $stat) {
            master_data::create([
                'type' => 'invoice_status',
                'code' => $v + 1,
                'name' => $stat,
            ]);
        }

        Event::create([
            'name' => 'NeutraDC Summit 2025',
            'location' => 'Jakarta, Indonesia',
            'description' => 'NeutraDC Summit 2025 is the premier event for data center professionals.',
            'start_date' => '2025-06-17',
            'end_date' => '2025-06-18',
            'is_active' => 1,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);
        
    }
}
