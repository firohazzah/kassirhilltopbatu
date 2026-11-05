<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class OrderNumberService
{
    public function generate()
    {
        return DB::transaction(function () {

            $today = now();
            $date = $today->toDateString();

            // Senin = A, Selasa = B, ... Minggu = G
            $dayCodes = [
                1 => 'A',
                2 => 'B',
                3 => 'C',
                4 => 'D',
                5 => 'E',
                6 => 'F',
                7 => 'G',
            ];

            $dayCode = $dayCodes[$today->dayOfWeekIso];

            // Contoh: 0511
            $dateCode = $today->format('dm');

            // Prefix nomor antrean, contoh: D0511
            $prefix = $dayCode . $dateCode;

            // Buat counter jika belum tersedia
            DB::table('daily_order_counters')->insertOrIgnore([
                'order_date' => $date,
                'last_number' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Kunci counter agar tidak digunakan bersamaan
            $counter = DB::table('daily_order_counters')
                ->where('order_date', $date)
                ->lockForUpdate()
                ->first();

            $nextNumber = (int) $counter->last_number + 1;

            // Simpan nomor terakhir
            DB::table('daily_order_counters')
                ->where('order_date', $date)
                ->update([
                    'last_number' => $nextNumber,
                    'updated_at' => now(),
                ]);

            // Hasil: D0511001
            return $prefix . str_pad(
                $nextNumber,
                3,
                '0',
                STR_PAD_LEFT
            );
        });
    }
}