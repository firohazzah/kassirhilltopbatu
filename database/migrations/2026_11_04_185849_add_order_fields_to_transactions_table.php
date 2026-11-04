
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('order_number')->nullable()->unique();
            $table->enum('order_type', ['cashier', 'kiosk'])
                ->default('cashier');

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'refunded'
            ])->default('paid');

            $table->enum('order_status', [
                'waiting',
                'processing',
                'completed',
                'cancelled'
            ])->default('completed');
        });

        // Sinkronisasi status transaksi lama
        DB::table('transactions')
            ->where('status', 'cancelled')
            ->update([
                'payment_status' => 'failed',
                'order_status' => 'cancelled'
            ]);
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropUnique(['order_number']);
            $table->dropColumn([
                'order_number',
                'order_type',
                'payment_status',
                'order_status'
            ]);
        });
    }
};
