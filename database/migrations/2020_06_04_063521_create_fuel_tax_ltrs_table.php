<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuelTaxLtrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_tax_ltrs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');
            $table->unsignedBigInteger('period_id');
            $table->foreign('period_id')
                ->references('id')
                ->on('periods')
                ->onDelete('cascade');
            $table->date('date');
            $table->double('ltr',6,3);
            $table->double('rate', 6, 3);
            $table->double('amount', 6, 3);
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
        Schema::dropIfExists('fuel_tax_ltrs');
    }
}
