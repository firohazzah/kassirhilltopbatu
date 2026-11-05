<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeTransactionOrderNumberUnique extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropUnique('transactions_order_number_unique');

            $table->date('order_date')
                ->nullable()
                ->after('order_number');
        });

        DB::statement("
            UPDATE transactions
            SET order_date = DATE(created_at)
            WHERE order_date IS NULL
        ");

        Schema::table('transactions', function (Blueprint $table) {
            $table->unique(
                ['order_date', 'order_number'],
                'transactions_order_date_number_unique'
            );
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropUnique(
                'transactions_order_date_number_unique'
            );

            $table->dropColumn('order_date');

            $table->unique(
                'order_number',
                'transactions_order_number_unique'
            );
        });
    }
}