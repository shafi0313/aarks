<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('industry_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('industry_category_profession', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('profession_id');
            $table->foreign('profession_id')->references('id')->on('professions');
            $table->unsignedBigInteger('industry_category_id');
            $table->foreign('industry_category_id')->references('id')->on('industry_categories');
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
        Schema::dropIfExists('industry_category_profession');
        Schema::dropIfExists('industry_categories');
        Schema::dropIfExists('professions');
    }
}
