<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        if (!Hotel::query()->exists()) {
            return;
        }

        $featured = Hotel::query()
            ->where('status', 'active')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        foreach ($featured as $index => $hotel) {
            $hotel->update([
                'is_featured' => true,
                'featured_order' => $index + 1,
            ]);
        }
    }
}
