<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\GroupClass;
use App\Models\ClassMembership;

class MembershipPackageSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // First, create Group Classes
        $reformerClass = GroupClass::firstOrCreate([
            'name' => 'Reformer Class',
            'category' => GroupClass::CATEGORY_REFORMER,
            'level' => GroupClass::LEVEL_INTERMEDIATE,
            'description' => 'Reformer classes focusing on strength, flexibility, and alignment',
            'is_active' => true,
        ]);

        $chairClass = GroupClass::firstOrCreate([
            'name' => 'Chair Class', 
            'category' => GroupClass::CATEGORY_CHAIR,
            'level' => GroupClass::LEVEL_BEGINNER,
            'description' => 'Chair classes for targeted muscle strengthening and stability',
            'is_active' => true,
        ]);

        $functionalClass = GroupClass::firstOrCreate([
            'name' => 'Functional Class',
            'category' => GroupClass::CATEGORY_FUNCTIONAL,
            'level' => GroupClass::LEVEL_INTERMEDIATE,
            'description' => 'Functional movement classes for everyday strength and mobility',
            'is_active' => true,
        ]);

        // Create SIGNATURE CLASS PACK packages
        
        // 1. The Core Series
        $coreSeriesPackage = Product::firstOrCreate([
            'name' => 'The Core Series',
        ], [
            'description' => 'Perfect introduction to Reformer and Chair classes. Build your foundation with our signature equipment.',
            'price' => 980000,
            'valid_until' => 20,
            'is_active' => true,
        ]);

        // 2. Elevate Pack
        $elevatePackage = Product::firstOrCreate([
            'name' => 'Elevate Pack',
        ], [
            'description' => 'Take your practice to the next level with more sessions and extended validity.',
            'price' => 1840000,
            'valid_until' => 30,
            'is_active' => true,
        ]);

        // 3. Aligné Flow
        $aligneFlowPackage = Product::firstOrCreate([
            'name' => 'Aligné Flow',
        ], [
            'description' => 'Our premium package for dedicated practitioners. Maximum flexibility and value.',
            'price' => 2388000,
            'valid_until' => 60,
            'is_active' => true,
        ]);

        // Create Functional Package (example)
        $functionalPackage = Product::firstOrCreate([
            'name' => 'Functional Movement Pack',
        ], [
            'description' => 'Focus on functional movement patterns for everyday strength and mobility.',
            'price' => 1500000,
            'valid_until' => 30,
            'is_active' => true,
        ]);

        // Link packages to group classes with quotas
        
        // The Core Series - 4x classes (2x Reformer + 2x Chair)
        ClassMembership::firstOrCreate([
            'membership_id' => $coreSeriesPackage->id,
            'class_id' => $reformerClass->id,
        ], [
            'quota' => 2,
        ]);

        ClassMembership::firstOrCreate([
            'membership_id' => $coreSeriesPackage->id,
            'class_id' => $chairClass->id,
        ], [
            'quota' => 2,
        ]);

        // Elevate Pack - 8x classes (4x Reformer + 4x Chair)
        ClassMembership::firstOrCreate([
            'membership_id' => $elevatePackage->id,
            'class_id' => $reformerClass->id,
        ], [
            'quota' => 4,
        ]);

        ClassMembership::firstOrCreate([
            'membership_id' => $elevatePackage->id,
            'class_id' => $chairClass->id,
        ], [
            'quota' => 4,
        ]);

        // Aligné Flow - 12x classes (6x Reformer + 6x Chair)
        ClassMembership::firstOrCreate([
            'membership_id' => $aligneFlowPackage->id,
            'class_id' => $reformerClass->id,
        ], [
            'quota' => 6,
        ]);

        ClassMembership::firstOrCreate([
            'membership_id' => $aligneFlowPackage->id,
            'class_id' => $chairClass->id,
        ], [
            'quota' => 6,
        ]);

        // Functional Package - 8x Functional classes
        ClassMembership::firstOrCreate([
            'membership_id' => $functionalPackage->id,
            'class_id' => $functionalClass->id,
        ], [
            'quota' => 8,
        ]);

        $this->command->info('Membership packages and class relationships created successfully!');
        $this->command->info('Created packages:');
        $this->command->info('- The Core Series (4x classes, 20 days validity)');
        $this->command->info('- Elevate Pack (8x classes, 30 days validity)');
        $this->command->info('- Aligné Flow (12x classes, 60 days validity)');
        $this->command->info('- Functional Movement Pack (8x classes, 30 days validity)');
    }
}
