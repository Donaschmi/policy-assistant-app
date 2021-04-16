<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_actors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('users');
            $table->foreignId('actor_id')->constrained('actors');
            $table->string('fullname')->nullable();
            $table->string('phone_number')->nullable();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
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
        Schema::dropIfExists('user_actor');
    }
}
