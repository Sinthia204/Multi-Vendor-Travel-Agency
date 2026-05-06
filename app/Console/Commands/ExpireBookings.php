<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class ExpireBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire pending bookings older than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $expiredCount = Booking::query()
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subHours(24))
            ->update(['status' => 'cancelled']);

        $this->info("Expired {$expiredCount} pending booking(s).");

        return self::SUCCESS;
    }
}
