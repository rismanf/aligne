<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\GroupClass;
use App\Models\Classes;
use App\Models\Trainer;
use App\Models\ClassMembership;
use App\Models\UserMembership;
use Illuminate\Support\Facades\Hash;

class FitnessAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏃‍♀️ Creating Fitness App Sample Data...');

        // Create Group Classes with proper categories
        $reformerGroup = GroupClass::create([
            'name' => 'REFORMER CLASS',
            'description' => 'Pilates reformer classes for strength, flexibility, and core stability',
            'category' => 'reformer',
            'level' => 'intermediate',
            'is_active' => true,
        ]);

        $chairGroup = GroupClass::create([
            'name' => 'CHAIR CLASS',
            'description' => 'Chair-based pilates exercises for targeted muscle work',
            'category' => 'chair',
            'level' => 'beginner',
            'is_active' => true,
        ]);

        $functionalGroup = GroupClass::create([
            'name' => 'FUNCTIONAL CLASS',
            'description' => 'Functional movement training for daily life activities',
            'category' => 'functional',
            'level' => 'advanced',
            'is_active' => true,
        ]);

        // Create Trainers
        $trainer1 = Trainer::create([
            'name' => 'Sarah Johnson',
            'phone' => '081234567890',
            'title' => 'Reformer & Chair Specialist',
            'description' => 'Certified Pilates instructor with 5 years of experience in reformer and chair classes.',
            'is_active' => true,
        ]);

        $trainer2 = Trainer::create([
            'name' => 'Mike Chen',
            'phone' => '081234567891',
            'title' => 'Functional Training Expert',
            'description' => 'Functional movement specialist with 7 years of experience in rehabilitation and fitness.',
            'is_active' => true,
        ]);

        $trainer3 = Trainer::create([
            'name' => 'Lisa Wong',
            'phone' => '081234567892',
            'title' => 'Chair & Stretching Specialist',
            'description' => 'Expert in chair-based exercises and therapeutic stretching with 4 years of experience.',
            'is_active' => true,
        ]);

        // Create REFORMER Classes
        $reformerClass1 = Classes::create([
            'name' => 'The Core Series',
            'description' => 'Focus on core strength and stability using reformer equipment',
            'group_class_id' => $reformerGroup->id,
            'group_class' => $reformerGroup->name,
            'level_class' => 'beginner',
            'is_active' => true,
        ]);

        $reformerClass2 = Classes::create([
            'name' => 'Elevate Flow',
            'description' => 'Advanced reformer sequences for experienced practitioners',
            'group_class_id' => $reformerGroup->id,
            'group_class' => $reformerGroup->name,
            'level_class' => 'intermediate',
            'is_active' => true,
        ]);

        $reformerClass3 = Classes::create([
            'name' => 'Balance Base',
            'description' => 'Foundation reformer class focusing on balance and alignment',
            'group_class_id' => $reformerGroup->id,
            'group_class' => $reformerGroup->name,
            'level_class' => 'beginner',
            'is_active' => true,
        ]);

        // Create CHAIR Classes
        $chairClass1 = Classes::create([
            'name' => 'Chair Play',
            'description' => 'Playful and dynamic chair exercises for all levels',
            'group_class_id' => $chairGroup->id,
            'group_class' => $chairGroup->name,
            'level_class' => 'beginner',
            'is_active' => true,
        ]);

        $chairClass2 = Classes::create([
            'name' => 'Chairful Moves',
            'description' => 'Precise and controlled chair movements',
            'group_class_id' => $chairGroup->id,
            'group_class' => $chairGroup->name,
            'level_class' => 'intermediate',
            'is_active' => true,
        ]);

        // Create FUNCTIONAL Classes
        $functionalClass1 = Classes::create([
            'name' => 'Soul Stretch',
            'description' => 'Functional stretching and mobility work',
            'group_class_id' => $functionalGroup->id,
            'group_class' => $functionalGroup->name,
            'level_class' => 'beginner',
            'is_active' => true,
        ]);

        $functionalClass2 = Classes::create([
            'name' => 'Melt & Reset',
            'description' => 'Recovery and restoration functional movements',
            'group_class_id' => $functionalGroup->id,
            'group_class' => $functionalGroup->name,
            'level_class' => 'intermediate',
            'is_active' => true,
        ]);

        $functionalClass3 = Classes::create([
            'name' => 'Align & Release',
            'description' => 'Alignment-focused functional training',
            'group_class_id' => $functionalGroup->id,
            'group_class' => $functionalGroup->name,
            'level_class' => 'advanced',
            'is_active' => true,
        ]);

        // Create SIGNATURE CLASS PACK Membership Products (sesuai spesifikasi yang benar)
        $coreSeriesPackage = Product::create([
            'name' => 'The Core Series',
            'description' => '4x Reformer / Chair Class',
            'price' => 980000,
            'valid_until' => 20,
            'is_active' => true,
        ]);

        $elevatePackage = Product::create([
            'name' => 'Elevate Pack',
            'description' => '8x Reformer / Chair Class',
            'price' => 1840000,
            'valid_until' => 30,
            'is_active' => true,
        ]);

        $aligneFlowPackage = Product::create([
            'name' => 'Aligné Flow',
            'description' => '12x Reformer / Chair Class',
            'price' => 2388000,
            'valid_until' => 60,
            'is_active' => true,
        ]);

        // PERBAIKAN: Create Class Memberships dengan konsep yang benar
        // SIGNATURE CLASS PACK memberikan total quota yang bisa digunakan fleksibel untuk REFORMER atau CHAIR
        
        // The Core Series Package (4x classes total - bisa semua REFORMER, semua CHAIR, atau kombinasi)
        ClassMembership::create([
            'membership_id' => $coreSeriesPackage->id,
            'class_id' => $reformerGroup->id, // REFORMER GROUP
            'quota' => 4, // Total 4x classes bisa digunakan untuk reformer
        ]);
        ClassMembership::create([
            'membership_id' => $coreSeriesPackage->id,
            'class_id' => $chairGroup->id, // CHAIR GROUP
            'quota' => 4, // Total 4x classes bisa digunakan untuk chair
        ]);

        // Elevate Pack (8x classes total - bisa semua REFORMER, semua CHAIR, atau kombinasi)
        ClassMembership::create([
            'membership_id' => $elevatePackage->id,
            'class_id' => $reformerGroup->id, // REFORMER GROUP
            'quota' => 8, // Total 8x classes bisa digunakan untuk reformer
        ]);
        ClassMembership::create([
            'membership_id' => $elevatePackage->id,
            'class_id' => $chairGroup->id, // CHAIR GROUP
            'quota' => 8, // Total 8x classes bisa digunakan untuk chair
        ]);

        // Aligné Flow Package (12x classes total - bisa semua REFORMER, semua CHAIR, atau kombinasi)
        ClassMembership::create([
            'membership_id' => $aligneFlowPackage->id,
            'class_id' => $reformerGroup->id, // REFORMER GROUP
            'quota' => 12, // Total 12x classes bisa digunakan untuk reformer
        ]);
        ClassMembership::create([
            'membership_id' => $aligneFlowPackage->id,
            'class_id' => $chairGroup->id, // CHAIR GROUP
            'quota' => 12, // Total 12x classes bisa digunakan untuk chair
        ]);

        // Create test users
        $testUser = User::create([
            'name' => 'Test Member',
            'email' => 'member@test.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
        ]);

        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@aligne.com',
            'password' => Hash::make('admin123'),
            'phone' => '081234567899',
        ]);

        // Create admin role if not exists
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        
        // Assign admin role
        $adminUser->assignRole('admin');

        // Create schedule times for each group class
        $scheduleTimes = [
            // Reformer Class (group_class_id = 1)
            ['name' => 'Morning Session 1', 'time' => '06:30:00', 'group_class_id' => $reformerGroup->id, 'group_class_name' => 'REFORMER CLASS'],
            ['name' => 'Morning Session 2', 'time' => '07:30:00', 'group_class_id' => $reformerGroup->id, 'group_class_name' => 'REFORMER CLASS'],
            ['name' => 'Morning Session 3', 'time' => '08:30:00', 'group_class_id' => $reformerGroup->id, 'group_class_name' => 'REFORMER CLASS'],
            ['name' => 'Morning Session 4', 'time' => '09:30:00', 'group_class_id' => $reformerGroup->id, 'group_class_name' => 'REFORMER CLASS'],
            ['name' => 'Midday Session 1', 'time' => '11:00:00', 'group_class_id' => $reformerGroup->id, 'group_class_name' => 'REFORMER CLASS'],
            ['name' => 'Midday Session 2', 'time' => '12:00:00', 'group_class_id' => $reformerGroup->id, 'group_class_name' => 'REFORMER CLASS'],
            ['name' => 'Evening Session 1', 'time' => '17:00:00', 'group_class_id' => $reformerGroup->id, 'group_class_name' => 'REFORMER CLASS'],
            ['name' => 'Evening Session 2', 'time' => '18:00:00', 'group_class_id' => $reformerGroup->id, 'group_class_name' => 'REFORMER CLASS'],
            ['name' => 'Evening Session 3', 'time' => '19:00:00', 'group_class_id' => $reformerGroup->id, 'group_class_name' => 'REFORMER CLASS'],

            // Chair Class (group_class_id = 2)
            ['name' => 'Morning Chair 1', 'time' => '07:00:00', 'group_class_id' => $chairGroup->id, 'group_class_name' => 'CHAIR CLASS'],
            ['name' => 'Morning Chair 2', 'time' => '08:00:00', 'group_class_id' => $chairGroup->id, 'group_class_name' => 'CHAIR CLASS'],
            ['name' => 'Morning Chair 3', 'time' => '09:00:00', 'group_class_id' => $chairGroup->id, 'group_class_name' => 'CHAIR CLASS'],
            ['name' => 'Morning Chair 4', 'time' => '10:00:00', 'group_class_id' => $chairGroup->id, 'group_class_name' => 'CHAIR CLASS'],
            ['name' => 'Afternoon Chair 1', 'time' => '16:00:00', 'group_class_id' => $chairGroup->id, 'group_class_name' => 'CHAIR CLASS'],
            ['name' => 'Evening Chair 1', 'time' => '17:30:00', 'group_class_id' => $chairGroup->id, 'group_class_name' => 'CHAIR CLASS'],
            ['name' => 'Evening Chair 2', 'time' => '18:30:00', 'group_class_id' => $chairGroup->id, 'group_class_name' => 'CHAIR CLASS'],

            // Functional Class (group_class_id = 3)
            ['name' => 'Early Functional', 'time' => '06:00:00', 'group_class_id' => $functionalGroup->id, 'group_class_name' => 'FUNCTIONAL CLASS'],
            ['name' => 'Morning Functional 1', 'time' => '07:00:00', 'group_class_id' => $functionalGroup->id, 'group_class_name' => 'FUNCTIONAL CLASS'],
            ['name' => 'Morning Functional 2', 'time' => '08:00:00', 'group_class_id' => $functionalGroup->id, 'group_class_name' => 'FUNCTIONAL CLASS'],
            ['name' => 'Evening Functional 1', 'time' => '17:00:00', 'group_class_id' => $functionalGroup->id, 'group_class_name' => 'FUNCTIONAL CLASS'],
            ['name' => 'Evening Functional 2', 'time' => '18:00:00', 'group_class_id' => $functionalGroup->id, 'group_class_name' => 'FUNCTIONAL CLASS'],
            ['name' => 'Evening Functional 3', 'time' => '19:00:00', 'group_class_id' => $functionalGroup->id, 'group_class_name' => 'FUNCTIONAL CLASS'],
        ];

        foreach ($scheduleTimes as $scheduleTime) {
            \App\Models\ScheduleTime::create($scheduleTime);
        }

        // Create sample user membership for testing
        $userMembership = UserMembership::create([
            'invoice_number' => 'INV-' . strtoupper(substr(md5(time()), 0, 6)),
            'user_id' => $testUser->id,
            'membership_id' => $coreSeriesPackage->id,
            'unique_code' => rand(100, 999),
            'price' => 980000,
            'total_price' => 980000 + rand(100, 999),
            'payment_method' => null,
            'payment_status' => 'paid',
            'status' => 'active',
            'starts_at' => \Carbon\Carbon::now(),
            'expires_at' => \Carbon\Carbon::now()->addDays(20),
        ]);

        // PERBAIKAN: Create user quotas berdasarkan konsep yang benar
        // The Core Series Package = 4x Classes total yang bisa digunakan fleksibel untuk REFORMER atau CHAIR
        \App\Models\UserKuota::create([
            'user_id' => $testUser->id,
            'product_id' => $coreSeriesPackage->id,
            'class_id' => $reformerGroup->id, // REFORMER GROUP
            'kuota' => 4, // Total 4x classes bisa digunakan untuk reformer
            'invoice_number' => $userMembership->invoice_number,
            'start_date' => \Carbon\Carbon::now(),
            'end_date' => \Carbon\Carbon::now()->addDays(20),
        ]);

        \App\Models\UserKuota::create([
            'user_id' => $testUser->id,
            'product_id' => $coreSeriesPackage->id,
            'class_id' => $chairGroup->id, // CHAIR GROUP
            'kuota' => 4, // Total 4x classes bisa digunakan untuk chair
            'invoice_number' => $userMembership->invoice_number,
            'start_date' => \Carbon\Carbon::now(),
            'end_date' => \Carbon\Carbon::now()->addDays(20),
        ]);

        // Create actual class schedules for the next 7 days
        $startDate = \Carbon\Carbon::today();
        $scheduleData = [];

        for ($day = 0; $day < 7; $day++) {
            $currentDate = $startDate->copy()->addDays($day);
            
            // Skip Sundays
            if ($currentDate->dayOfWeek === 0) {
                continue;
            }

            // Morning schedules (6:30 - 12:00)
            $morningTimes = ['06:30', '07:30', '08:30', '09:30', '11:00', '12:00'];
            // Evening schedules (17:00 - 19:00)
            $eveningTimes = ['17:00', '18:00', '19:00'];
            
            $allTimes = array_merge($morningTimes, $eveningTimes);
            
            foreach ($allTimes as $time) {
                // Randomly assign classes to time slots
                $classes = [$reformerClass1, $reformerClass2, $reformerClass3, $chairClass1, $chairClass2, $functionalClass1, $functionalClass2, $functionalClass3];
                $randomClass = $classes[array_rand($classes)];
                $randomTrainer = [$trainer1, $trainer2, $trainer3][array_rand([$trainer1, $trainer2, $trainer3])];
                
                $startTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $currentDate->format('Y-m-d') . ' ' . $time);
                $endTime = $startTime->copy()->addMinutes(60); // 1 hour classes
                
                $scheduleData[] = [
                    'name' => $randomClass->name . ' - ' . $currentDate->format('M j') . ' ' . $time,
                    'class_id' => $randomClass->id,
                    'trainer_id' => $randomTrainer->id,
                    'date' => $currentDate->format('Y-m-d'),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration' => 60, // 60 minutes
                    'capacity' => rand(8, 12),
                    'capacity_book' => 0,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert class schedules
        foreach ($scheduleData as $schedule) {
            \App\Models\ClassSchedules::create($schedule);
        }

        $this->command->info('✅ Fitness app sample data created successfully!');
        $this->command->line('');
        $this->command->info('📋 Created Data Summary:');
        $this->command->line('• Group Classes: 3 (Reformer, Chair, Functional)');
        $this->command->line('• Individual Classes: 8 classes total');
        $this->command->line('• Trainers: 3 certified trainers');
        $this->command->line('• Membership Packages: 3 signature packs');
        $this->command->line('• Test Users: 2 (member + admin)');
        $this->command->line('');
        $this->command->info('🔐 Login Credentials:');
        $this->command->line('Member: member@test.com / password');
        $this->command->line('Admin: admin@aligne.com / admin123');
        $this->command->line('');
        $this->command->info('📦 Membership Packages:');
        $this->command->line('• The Core Series: IDR 980,000 (4x classes, 20 days)');
        $this->command->line('• Elevate Pack: IDR 1,840,000 (8x classes, 30 days)');
        $this->command->line('• Aligné Flow: IDR 2,388,000 (12x classes, 60 days)');
        $this->command->line('');
        $this->command->info('🎯 Ready for testing complete user flow!');
    }
}
