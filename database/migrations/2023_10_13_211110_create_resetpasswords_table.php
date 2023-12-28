<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResetpasswordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resetpasswords', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('Heading')->nullable();
            $table->string('content')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('resetpasswords');
    }
}
