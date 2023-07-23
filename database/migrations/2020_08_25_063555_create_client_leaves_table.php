<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->double('rate', 8, 4)->nullable();
            $table->string('tools')->nullable();
            $table->double('fix_amt', 8, 4)->nullable();
            $table->string('period')->nullable();
            $table->tinyInteger('carry')->nullable();
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
        Schema::dropIfExists('client_leaves');
    }
}
