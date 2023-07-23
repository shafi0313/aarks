<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('profession_id')->constrained()->nullable()->onDelete('cascade');
            $table->unsignedBigInteger('chart_id')->index();
            $table->date('date');
            $table->string('tran_id')->index();
            $table->string('source')->index();
            $table->text('narration')->nullable();
            $table->double('percent', 14, 2)->nullable();
            $table->double('amount', 14, 2)->nullable();
            $table->double('old_percent', 14, 4)->nullable();
            $table->double('last_year_amount', 14, 2)->nullable();
            $table->timestamps();

            // $table->foreign('chart_id')->references('id')->on('client_account_codes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_entries');
    }
};
