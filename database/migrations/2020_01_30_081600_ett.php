<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Ett extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ett', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('zone_from', 50)->nullable();
            $table->string('zone_to', 50)->nullable();
            $table->string('travel_time', 50)->nullable();
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
        Schema::dropIfExists('ett');
    }
}
