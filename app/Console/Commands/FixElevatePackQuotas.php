<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserMembership;
use App\Models\UserKuota;
use Illuminate\Support\Facades\DB;

class FixElevatePackQuotas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:elevate-pack-quotas {--dry-run : Show what would be fixed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix Elevate Pack 8x quotas that were incorrectly set to 16 instead of 8';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('Running in DRY RUN mode - no changes will be made');
        }
        
        $this->info('Searching for Elevate Pack memberships with incorrect quotas...');
        
        // Find all active user memberships with Elevate Pack
        $elevatePackMemberships = UserMembership::whereHas('membership', function($query) {
            $query->where('name', 'like', '%elevate%')
                  ->orWhere('name', 'like', '%8x%');
        })
        ->where('status', 'active')
        ->with('membership')
        ->get();
        
        $this->info("Found {$elevatePackMemberships->count()} Elevate Pack memberships");
        
        $fixedCount = 0;
        $table = [];
        
        foreach ($elevatePackMemberships as $membership) {
            // Check if user has incorrect quota (more than 8 for combination packages)
            $userQuotas = UserKuota::where('user_id', $membership->user_id)
                ->where('class_id', 0) // Combination package quota
                ->where('invoice_number', $membership->invoice_number)
                ->where('end_date', '>', now())
                ->get();
                
            foreach ($userQuotas as $userQuota) {
                // Extract expected quota from membership name
                $expectedQuota = $this->extractQuotaFromName($membership->membership->name);
                
                if ($expectedQuota && $userQuota->kuota > $expectedQuota) {
                    $table[] = [
                        'User ID' => $membership->user_id,
                        'Membership' => $membership->membership->name,
                        'Current Quota' => $userQuota->kuota,
                        'Expected Quota' => $expectedQuota,
                        'Invoice' => $membership->invoice_number,
                    ];
                    
                    if (!$isDryRun) {
                        $userQuota->update(['kuota' => $expectedQuota]);
                        $this->info("✓ Fixed quota for user {$membership->user_id}: {$userQuota->kuota} → {$expectedQuota}");
                    }
                    
                    $fixedCount++;
                }
            }
        }
        
        if (!empty($table)) {
            $this->table(['User ID', 'Membership', 'Current Quota', 'Expected Quota', 'Invoice'], $table);
        }
        
        if ($isDryRun) {
            $this->warn("DRY RUN: Would fix {$fixedCount} user quotas");
            $this->info('Run without --dry-run to apply the fixes');
        } else {
            $this->info("✓ Fixed {$fixedCount} user quotas successfully!");
        }
        
        return 0;
    }
    
    /**
     * Extract quota number from membership name
     */
    private function extractQuotaFromName($membershipName)
    {
        // Pattern to match numbers followed by 'x' (e.g., "8x", "12x", "16x")
        if (preg_match('/(\d+)x/i', $membershipName, $matches)) {
            return (int) $matches[1];
        }
        
        // Pattern to match "Pack X" format (e.g., "Pack 8", "Pack 12")
        if (preg_match('/pack\s+(\d+)/i', $membershipName, $matches)) {
            return (int) $matches[1];
        }
        
        // Pattern to match standalone numbers in package names
        if (preg_match('/\b(\d+)\b/', $membershipName, $matches)) {
            $number = (int) $matches[1];
            // Only return reasonable quota numbers (between 1 and 50)
            if ($number >= 1 && $number <= 50) {
                return $number;
            }
        }
        
        return null;
    }
}
