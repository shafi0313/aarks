<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientWagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_wages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('period_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('financial_year')->nullable();
            $table->string('name');
            $table->string('link_group');
            $table->string('type');
            $table->double('regular_rate', 8, 4)->default(0);
            $table->double('hourly_rate', 8, 4)->default(0);
            $table->tinyInteger('action')->default(0);
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
        Schema::dropIfExists('client_wages');
    }
}
