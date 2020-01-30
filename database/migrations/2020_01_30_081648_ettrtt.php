<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Ettrtt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ettrtt', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('zone_from', 50)->nullable();
            $table->string('zone_to', 50)->nullable();
            $table->string('ett', 50)->nullable();
            $table->string('mean_rtt', 50)->nullable();
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
        Schema::dropIfExists('ettrtt');
    }
}
