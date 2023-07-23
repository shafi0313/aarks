<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuelLtrs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_ltrs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ftc_id');
            $table->foreign('ftc_id')
                ->references('id')
                ->on('fuel_tax_credits')
                ->onDelete('cascade');
            $table->date('ltr_date');
            $table->double('ltr', 6, 2);
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
        Schema::dropIfExists('fuel_ltrs');
    }
}
