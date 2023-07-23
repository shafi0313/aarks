<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJournalNumberToJournalEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->string('journal_number')->after('account_code')->nullable();
            $table->string('tran_id', 64)->index()->after('journal_number')->nullable();
            $table->boolean('is_edited')->after('is_posted')->default(false);
            // $table->unique(['journal_number','client_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            // $table->dropUnique(['journal_number','client_id']);
            $table->dropColumn(['journal_number','tran_id']);
        });
    }
}
