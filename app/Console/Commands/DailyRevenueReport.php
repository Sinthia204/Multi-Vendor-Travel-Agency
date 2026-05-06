<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyRevenueReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:daily-revenue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Log daily revenue totals from successful payments';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $start = now()->subDay()->startOfDay();
        $end = now()->subDay()->endOfDay();

        $totals = Payment::query()
            ->where('status', 'success')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('currency, SUM(amount) as total')
            ->groupBy('currency')
            ->get();

        if ($totals->isEmpty()) {
            $this->info('No revenue for the previous day.');
            return self::SUCCESS;
        }

        foreach ($totals as $row) {
            $message = "Daily revenue for {$start->toDateString()} ({$row->currency}): {$row->total}";
            Log::info($message);
            $this->info($message);
        }

        return self::SUCCESS;
    }
}
