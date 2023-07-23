<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsRolloverDepAssetNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dep_asset_names', function (Blueprint $table) {
            $table->unsignedInteger('rollover_year')->nullable()->index()->after('year');
            $table->unsignedInteger('batch')->nullable()->index()->after('id')->comment('client_id,Profession_id,parent_id');
            $table->boolean('is_rollover')->default(0)->nullable()->index()->after('rollover_year');
        });
        Schema::table('bank_statement_imports', function (Blueprint $table) {
            $table->string('tran_id', 64)->nullable()->index()->after('date');
        });
        Schema::table('bank_statement_inputs', function (Blueprint $table) {
            $table->string('tran_id', 64)->nullable()->index()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dep_asset_names', function (Blueprint $table) {
            $table->dropIndex(['is_rollover','rollover_year','batch']);
        });
        Schema::table('bank_statement_imports', function (Blueprint $table) {
            $table->dropIndex(['tran_id']);
        });
        Schema::table('bank_statement_inputs', function (Blueprint $table) {
            $table->dropIndex(['tran_id']);
        });
    }
}
