<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQueueMonitorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queue_statistics', function (Blueprint $table) {
            
            $table->string('id')->primary();
            
            $table->string('job');
            $table->string('queue');
            
            $table->unsignedBigInteger('queued')->nullable();
            $table->unsignedBigInteger('started')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('queue_statistics');
    }
}
