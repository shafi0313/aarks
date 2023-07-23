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
        Schema::create('business_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('profession_id')->constrained()->nullable()->onDelete('cascade');
            $table->unsignedBigInteger('chart_id')->index();
            $table->date('date');
            $table->string('tran_id')->index();
            $table->string('source')->index();
            $table->text('narration')->nullable();
            $table->double('last_amount', 14, 2)->nullable();
            $table->double('last_percent', 14, 2)->nullable();
            $table->longText('months')->nullable();
            // $table->double('percent', 14, 2)->nullable();
            // $table->double('amount', 14, 2)->nullable();

            $table->foreignId('account_code_id')->constrained('client_account_codes')->nullable()->onDelete('cascade');
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
        Schema::dropIfExists('business_plans');
    }
};
