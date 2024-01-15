<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('summmery');
            $table->text('description');
            $table->string('phone');
            $table->text('location');
            // $table->text('colorId');
            $table->string('calender_id');
            $table->dateTime('startdatetime');
            $table->dateTime('enddatetime');
            $table->text('status');
            $table->text('recurrence_type')->nullable();
            $table->text('recurrence_interval')->nullable();
            $table->text('recurrence_until')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendars');
    }
};
