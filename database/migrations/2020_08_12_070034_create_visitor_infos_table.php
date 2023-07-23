<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_infos', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('ip');
            $table->string('iso_code');
            $table->string('country');
            $table->string('city');
            $table->string('state_name');
            $table->string('postal_code');
            $table->string('lat');
            $table->string('lon');
            $table->string('currency');
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
        Schema::dropIfExists('visitor_infos');
    }
}
