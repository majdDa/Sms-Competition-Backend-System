<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commands', function (Blueprint $table) {
            $table->id();
            //$table->string('name',50) -> unique();
            $table->string('name', 50);
            $table->string('category', 50);
            $table->integer('category_id')->comment("'keyword' => 0, 'question' => 1, 'balance' => 2, 'help' => 3, 'deactivation' => 4");
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
        Schema::dropIfExists('commands');
    }
}