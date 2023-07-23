<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniversalColumnToProfessionAccountCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_code_profession', function (Blueprint $table) {
            $table->tinyInteger('is_universal')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_code_profession', function (Blueprint $table) {
            $table->dropColumn('is_universal');
        });
    }
}
