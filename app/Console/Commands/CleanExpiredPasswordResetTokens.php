<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanExpiredPasswordResetTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:clean-reset-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean expired password reset tokens (older than 30 minutes)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredTokens = DB::table('password_reset_tokens')
            ->where('created_at', '<', now()->subMinutes(30))
            ->count();

        if ($expiredTokens > 0) {
            DB::table('password_reset_tokens')
                ->where('created_at', '<', now()->subMinutes(30))
                ->delete();

            $this->info("Cleaned {$expiredTokens} expired password reset tokens.");
        } else {
            $this->info('No expired password reset tokens found.');
        }

        return 0;
    }
}
