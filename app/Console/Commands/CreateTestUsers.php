<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\GroupClass;
use App\Models\Classes;
use App\Models\Trainer;
use App\Models\ClassSchedules;
use App\Models\Membership;
use App\Models\UserMembership;
use Carbon\Carbon;

class CreateTestUsers extends Command
{
    protected $signature = 'test:create-users';
    protected $description = 'Create test users and data for testing admin booking functionality';

    public function handle()
    {
        $this->info('Creating test users and data...');

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin Test',
                'password' => bcrypt('password'),
                'is_guest' => false
            ]
        );
        $this->info('Admin created: ' . $admin->email);

        // Create member user
        $member = User::firstOrCreate(
            ['email' => 'member@test.com'],
            [
                'name' => 'Test Member',
                'password' => bcrypt('password'),
                'is_guest' => false
            ]
        );
        $this->info('Member created: ' . $member->email);

        // Create group class if not exists
        $groupClass = GroupClass::firstOrCreate(
            ['name' => 'REFORMER'],
            ['sort_order' => 1]
        );
        $this->info('Group class created: ' . $groupClass->name);

        // Create class if not exists
        $class = Classes::firstOrCreate(
            ['name' => 'Pilates Reformer Beginner'],
            [
                'group_class_id' => $groupClass->id,
                'level_class' => 'Beginner',
                'description' => 'Beginner level Pilates Reformer class'
            ]
        );
        $this->info('Class created: ' . $class->name);

        // Create trainer if not exists
        $trainer = Trainer::firstOrCreate(
            ['name' => 'Test Trainer'],
            [
                'email' => 'trainer@test.com',
                'phone' => '081234567890'
            ]
        );
        $this->info('Trainer created: ' . $trainer->name);

        // Create membership if not exists
        $membership = Membership::firstOrCreate(
            ['name' => 'Monthly Reformer'],
            [
                'price' => 1500000,
                'duration_days' => 30,
                'class_quota' => 8,
                'description' => 'Monthly membership for Reformer classes'
            ]
        );
        $this->info('Membership created: ' . $membership->name);

        // Create user membership for the test member
        $userMembership = UserMembership::firstOrCreate(
            [
                'user_id' => $member->id,
                'membership_id' => $membership->id
            ],
            [
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(30),
                'status' => 'active',
                'payment_status' => 'paid',
                'remaining_quota' => 8
            ]
        );
        $this->info('User membership created for: ' . $member->name);

        // Create test schedule for today
        $schedule = ClassSchedules::firstOrCreate(
            [
                'class_id' => $class->id,
                'trainer_id' => $trainer->id,
                'date' => Carbon::today()->format('Y-m-d'),
                'start_time' => Carbon::today()->setTime(10, 0, 0)
            ],
            [
                'name' => $class->name . ' - 10:00',
                'end_time' => Carbon::today()->setTime(11, 0, 0),
                'duration' => 60,
                'capacity' => 8,
                'capacity_book' => 0,
                'is_active' => true
            ]
        );
        $this->info('Schedule created: ' . $schedule->name);

        $this->info('Test data creation completed!');
        $this->info('Login credentials:');
        $this->info('Admin: admin@test.com / password');
        $this->info('Member: member@test.com / password');
    }
}
