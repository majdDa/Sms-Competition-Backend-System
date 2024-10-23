<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingHistorySyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_history_sy', function (Blueprint $table) {
            $table->id();
            $table->string('gsm', 12);
            $table->string('command', 50);
            $table->string('response', 25)->nullable();
            $table->integer('status')->default(0);
            $table->integer('attempt_number')->default(1);
            $table->datetime('attemp_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pending_history_sy');
    }
}
