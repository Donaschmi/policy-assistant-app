<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->morphs('triggerable');
            $table->string('attribute');
            $table->string('operator');
            $table->string('value');
            $table->string('sentence');
            $table->integer('mental_health')->default(0);
            $table->integer('physical_health')->default(0);
            $table->integer('reactivity')->default(0);
            $table->integer('privacy')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
