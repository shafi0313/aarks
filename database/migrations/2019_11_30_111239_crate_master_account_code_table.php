<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateMasterAccountCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_account_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_code_id');
            $table->foreign('account_code_id')->references('id')->on('account_codes');
            modificationFields($table);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('professions', function (Blueprint $table) {
            $table->boolean('is_master_account_code_synced')->default(false);
            $table->boolean('can_perform_sync')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_account_codes');
        Schema::table('professions', function (Blueprint $table) {
            $table->dropColumn('is_master_account_code_synced');
            $table->dropColumn('can_perform_sync');
        });
    }
}
