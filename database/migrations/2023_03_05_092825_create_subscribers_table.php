<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('gsm', 12)->unique();
            $table->boolean('sub_status');
            $table->date('sub_date');
            $table->boolean('operator');
            $table->date('cancel_date')->nullable();
            $table->datetime('last_response_date');
            $table->boolean('last_answer')->default(0);
            $table->integer('question_order');
            $table->integer('short_code');
            $table->bigInteger('score');
            $table->string('canceled_by', 50)->nullable();
            $table->string('activated_by', 50)->nullable();
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
        Schema::dropIfExists('subscribers');
    }
}
