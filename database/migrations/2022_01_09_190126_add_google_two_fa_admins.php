<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoogleTwoFaAdmins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->text('two_factor_secret')->after('email')->nullable();
            $table->text('two_factor_recovery_codes')->after('two_factor_secret')->nullable();
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->text('two_factor_secret')->after('email')->nullable();
            $table->text('two_factor_recovery_codes')->after('two_factor_secret')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['two_factor_secret','two_factor_recovery_codes']);
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['two_factor_secret','two_factor_recovery_codes']);
        });
    }
}
