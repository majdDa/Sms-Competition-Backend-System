<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingGsmMtnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_gsm_mtn', function (Blueprint $table) {
            $table->id();
            $table->string('gsm', 25)->unique();
            $table->string('command', 50);
            $table->string('response', 25)->nullable();
            $table->integer('status')->default(0);
            $table->integer('attempt')->default(1);
            $table->datetime('attempt_date');
            $table->string('renewal_by')->nullable();
            $table->string('mt')->nullable();
            $table->timestamps();
            $table->tinyInteger('is_processed')->nullable()->default(0);
            $table->tinyInteger('cancel_balance_mt')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pending_gsm_mtn');
    }
}