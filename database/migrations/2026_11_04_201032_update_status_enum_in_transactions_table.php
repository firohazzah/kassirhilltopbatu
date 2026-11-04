<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateStatusEnumInTransactionsTable extends Migration
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
            UPDATE transactions
            SET status = 'paid'
            WHERE status = 'pending'
        ");

        DB::statement("
            ALTER TABLE transactions
            MODIFY status ENUM('paid', 'cancelled')
            NOT NULL DEFAULT 'paid'
        ");
    }
}