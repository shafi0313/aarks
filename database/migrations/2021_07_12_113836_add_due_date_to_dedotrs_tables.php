<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDueDateToDedotrsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dedotrs', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('your_ref');
        });
        Schema::table('creditors', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('your_ref');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dedotrs', function (Blueprint $table) {
            $table->dropColumn('due_date');
        });
        Schema::table('creditors', function (Blueprint $table) {
            $table->dropColumn('due_date');
        });
    }
}
