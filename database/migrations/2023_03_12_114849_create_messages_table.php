<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['true_Answer', 'false_Answer', 'keyword', 'help', 'balance', 'welcoming', 'unsubscribe', 'pending_activation', 'pending_deActivation', 'renewal', 'invalid', 'cancelation', 'final_keyword', 'invalid_last_answer']);
            $table->string('message', 2000);
            $table->integer('order');
            $table->integer('category_id');
            $table->string('category_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
