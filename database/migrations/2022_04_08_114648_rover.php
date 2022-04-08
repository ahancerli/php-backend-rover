<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Rover extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rover', function (Blueprint $table) {
            $table->id();
            $table->integer('plateauId')->unsigned();
            $table->string('name');
            $table->enum('direction', ['N', 'S', 'W','E'])->default('N');
            $table->json('location')->default(json_encode([0,0]));
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
        Schema::drop('rover');
    }
}
