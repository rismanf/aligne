<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateQuotaStrategySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Update existing packages to set appropriate quota strategies
        $packages = Product::all();
        
        foreach ($packages as $package) {
            $packageName = strtolower($package->name);
            
            // Set flexible quota for packages that should share quota across class types
            if (str_contains($packageName, 'flow') || 
                str_contains($packageName, 'elevate') ||
                str_contains($packageName, 'reformer / chair') ||
                str_contains($packageName, 'reformer/chair') ||
                str_contains($packageName, 'signature class pack')) {
                
                $package->update(['quota_strategy' => 'flexible']);
                $this->command->info("Updated '{$package->name}' to flexible quota strategy");
            } else {
                // Set fixed quota for packages with specific quotas per class type
                $package->update(['quota_strategy' => 'fixed']);
                $this->command->info("Updated '{$package->name}' to fixed quota strategy");
            }
        }
        
        $this->command->info('Quota strategy update completed!');
    }
}
