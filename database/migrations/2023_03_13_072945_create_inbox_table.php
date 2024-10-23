<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInboxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbox', function (Blueprint $table) {

            $table->id();
            $table->string('gsm');
            $table->unsignedBigInteger('subscriber_id');
            $table->foreign('subscriber_id')->references('id')->on('subscribers');
            $table->string('sms', 240);
            $table->string('status', 50)->default(0);
            $table->timestamp('sms_date')->nullable();
            $table->integer('short_code');
            $table->integer('operator');
            $table->unsignedBigInteger('question_id')->nullable();
            $table->foreign('question_id')->references('id')->on('questions');
            $table->unsignedBigInteger('keyword_id')->nullable();
            $table->foreign('keyword_id')->references('id')->on('key_words');
            $table->string('sms_mt', 2000)->nullable();
            $table->string('operator_mt_response', 2000)->nullable();
            $table->bigInteger('points')->nullable();;
            $table->bigInteger('request_id')->nullable();
            $table->unsignedBigInteger('pending_id_mtn')->nullable();
            // $table->foreign('pending_id_mtn')->references('id')->on('pending_history_mtn');
            $table->unsignedBigInteger('pending_id_sy')->nullable();
            //   $table->foreign('pending_id_sy')->references('id')->on('pending_history_sy');
            $table->unsignedBigInteger('command_id')->nullable();
            $table->foreign('command_id')->references('id')->on('commands');
            $table->string('type')->nullable();
            $table->timestamp('op_timestamp')->nullable();
            $table->tinyInteger('lang_id')->nullable();
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
        Schema::dropIfExists('inbox');
    }
}
