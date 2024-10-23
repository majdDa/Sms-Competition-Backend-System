<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeasersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teasers', function (Blueprint $table) {
            $table->id();
            $table->text('mtxt');
            $table->dateTime('sending_date');
            $table->string('ctg');
            $table->string('status_mtn');
            $table->string('status_syriatel');
            $table->string('ip');
            $table->integer('op_id');
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
        Schema::dropIfExists('teasers');
    }
}
