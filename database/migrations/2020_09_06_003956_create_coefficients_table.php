<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoefficientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coefficients', function (Blueprint $table) {
            $table->id();
            $table->string('holding_type');
            $table->double('weekly_earning',10,4);
            $table->double('per_dollar_a',10,4);
            $table->double('per_dollar_b',10,4);
            $table->integer('year');
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
        Schema::dropIfExists('coefficients');
    }
}
