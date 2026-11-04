<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPendingStatusToTransactionsTable extends Migration
{
    public function up()
    {
        DB::statement("
            ALTER TABLE transactions
            MODIFY status ENUM('paid', 'cancelled', 'pending')
            NOT NULL DEFAULT 'paid'
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE transactions
            MODIFY status ENUM('paid', 'cancelled')
            NOT NULL DEFAULT 'paid'
        ");
    }
}