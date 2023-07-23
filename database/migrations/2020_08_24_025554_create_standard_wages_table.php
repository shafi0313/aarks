<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandardWagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standard_wages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('link_group');
            $table->double('regular_rate',8,4)->default(0);
            $table->double('hourly_rate',8,4)->default(0);
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
        Schema::dropIfExists('standard_wages');
    }
}
