<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class OrderNumberService
{
    public function generate()
    {
        return DB::transaction(function () {
            $today = now()->toDateString();

            DB::table('daily_order_counters')->insertOrIgnore([
                'order_date' => $today,
                'last_number' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $counter = DB::table('daily_order_counters')
                ->where('order_date', $today)
                ->lockForUpdate()
                ->first();

            $nextNumber = $counter->last_number + 1;

            DB::table('daily_order_counters')
                ->where('order_date', $today)
                ->update([
                    'last_number' => $nextNumber,
                    'updated_at' => now(),
                ]);

            return 'A-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });
    }
}