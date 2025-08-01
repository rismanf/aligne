<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, run the previous migrations
        $this->runPreviousMigrations();
        
        // Check if user_produks table exists and has data
        if (Schema::hasTable('user_produks')) {
            $userProduks = DB::table('user_produks')->whereNull('deleted_at')->get();
            
            foreach ($userProduks as $userProduk) {
                // Check if record already exists in user_memberships
                $exists = DB::table('user_memberships')
                    ->where('invoice_number', $userProduk->invoice_number)
                    ->where('user_id', $userProduk->user_id)
                    ->exists();
                    
                if (!$exists) {
                    // Calculate expiration date based on product validity
                    $product = DB::table('products')->where('id', $userProduk->product_id)->first();
                    $expiresAt = null;
                    
                    if ($product && isset($product->valid_until) && $product->valid_until) {
                        $startDate = $userProduk->confirmed_at ?? $userProduk->created_at;
                        $expiresAt = date('Y-m-d H:i:s', strtotime($startDate . ' + ' . $product->valid_until . ' days'));
                    }
                    
                    // Determine status based on payment_status
                    $status = 'pending';
                    if (strtolower($userProduk->payment_status) === 'paid') {
                        $status = 'active';
                    } elseif (strtolower($userProduk->payment_status) === 'unpaid') {
                        $status = 'pending';
                    }
                    
                    DB::table('user_memberships')->insert([
                        'invoice_number' => $userProduk->invoice_number,
                        'user_id' => $userProduk->user_id,
                        'membership_id' => $userProduk->product_id,
                        'unique_code' => $userProduk->unique_code ?? null,
                        'price' => $userProduk->price ?? 0,
                        'total_price' => $userProduk->total_price ?? 0,
                        'quota' => $userProduk->kuota ?? null,
                        'payment_method' => $userProduk->payment_method ?? null,
                        'payment_proof' => $userProduk->payment_proof ?? null,
                        'payment_status' => strtolower($userProduk->payment_status ?? 'unpaid'),
                        'status' => $status,
                        'starts_at' => $userProduk->confirmed_at,
                        'expires_at' => $expiresAt,
                        'paid_at' => $userProduk->paid_at ?? null,
                        'confirmed_by' => $userProduk->confirmed_by ?? null,
                        'confirmed_at' => $userProduk->confirmed_at ?? null,
                        'created_by_id' => $userProduk->created_by_id ?? null,
                        'updated_by_id' => $userProduk->updated_by_id ?? null,
                        'created_at' => $userProduk->created_at,
                        'updated_at' => $userProduk->updated_at,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear user_memberships table
        DB::table('user_memberships')->truncate();
    }
    
    /**
     * Run previous migrations if they haven't been run
     */
    private function runPreviousMigrations()
    {
        try {
            // Check if columns exist, if not run the migrations
            if (!Schema::hasColumn('user_memberships', 'status')) {
                Artisan::call('migrate', ['--path' => 'database/migrations/2025_01_15_000001_update_user_memberships_table.php']);
            }
            
            if (!Schema::hasColumn('group_classes', 'category')) {
                Artisan::call('migrate', ['--path' => 'database/migrations/2025_01_15_000002_update_group_classes_table.php']);
            }
            
            if (!Schema::hasColumn('class_bookings', 'user_id')) {
                Artisan::call('migrate', ['--path' => 'database/migrations/2025_01_15_000003_update_class_bookings_table.php']);
            }
        } catch (Exception $e) {
            // Migrations might already be run, continue
        }
    }
};
