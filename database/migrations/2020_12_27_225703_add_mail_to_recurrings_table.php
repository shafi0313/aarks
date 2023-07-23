<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMailToRecurringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recurrings', function (Blueprint $table) {
            $table->tinyInteger('recurring')->default(1);
            $table->date('untill_date')->nullable();
            $table->string('unlimited')->default(0);
            $table->string('recur_tran')->nullable();
            $table->string('mail_to')->nullable();
            $table->boolean('is_expire')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recurrings', function (Blueprint $table) {
            $table->dropColumn(['untill_date','mail_to','recurring','unlimited','recur_tran','is_expire']);
        });
    }
}
