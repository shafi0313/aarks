<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientSuperannuationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_superannuations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->double('e_rate', 8, 4)->nullable();
            $table->string('e_tools')->nullable();
            $table->double('e_fix_amt', 8, 4)->nullable();
            $table->string('e_period')->nullable();
            $table->double('e_excl_amt', 8, 4)->nullable();
            $table->double('t_rate', 8, 4)->nullable();
            $table->string('t_tools')->nullable();
            $table->double('t_fix_amt', 8, 4)->nullable();
            $table->string('t_period')->nullable();
            $table->double('t_excl_amt', 8, 4)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_superannuations');
    }
}
