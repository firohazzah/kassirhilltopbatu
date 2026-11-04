<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyOrderCountersTable extends Migration
{
    public function up()
    {
        Schema::create('daily_order_counters', function (Blueprint $table) {
            $table->id();
            $table->date('order_date')->unique();
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_order_counters');
    }
}