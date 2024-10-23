<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingHistoryMTNTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_history_mtn', function (Blueprint $table) {
            $table->id();
            $table->string('gsm', 25);
            $table->string('command', 50);
            $table->string('response', 25)->nullable();
            $table->integer('status')->default(0);
            $table->integer('attempt')->default(1);
            $table->datetime('attempt_date');
            $table->string('renewal_by')->nullable();
            $table->string('mt')->nullable();
            $table->string('op_response')->nullable();
            $table->integer('cancel_balance_mt')->default(0);
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
        Schema::dropIfExists('pending_history_mtn');
    }
}
