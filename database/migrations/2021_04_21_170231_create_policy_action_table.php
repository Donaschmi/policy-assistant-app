<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePolicyActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policy_action', function (Blueprint $table) {
            $table->foreignId('policy_id')
                ->constrained('policies')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('action_id')
                ->constrained('actions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->index('policy_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('policy_action');
    }
}
