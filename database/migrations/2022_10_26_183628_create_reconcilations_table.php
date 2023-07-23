<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReconcilationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reconcilations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('profession_id')->constrained()->onDelete('cascade');
            $table->foreignId('period_id')->constrained()->onDelete('cascade');
            $table->string('tran_id')->index();
            $table->unsignedInteger('year')->nullable()->index();
            $table->string('item')->nullable();
            $table->boolean('is_posted')->default(0);
            $table->unsignedDouble('jul_sep_gl', 14, 2)->nullable();
            $table->unsignedDouble('jul_sep_ato', 14, 2)->nullable();
            // $table->unsignedDouble('jul_sep_diff', 14, 2)->nullable();
            $table->unsignedDouble('oct_dec_gl', 14, 2)->nullable();
            $table->unsignedDouble('oct_dec_ato', 14, 2)->nullable();
            // $table->unsignedDouble('oct_dec_diff', 14, 2)->nullable();
            $table->unsignedDouble('jan_mar_gl', 14, 2)->nullable();
            $table->unsignedDouble('jan_mar_ato', 14, 2)->nullable();
            // $table->unsignedDouble('jan_mar_diff', 14, 2)->nullable();
            $table->unsignedDouble('apr_jun_gl', 14, 2)->nullable();
            $table->unsignedDouble('apr_jun_ato', 14, 2)->nullable();
            // $table->unsignedDouble('apr_jun_gl_diff', 14, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('reconcilation_taxes', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('reconcilation_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('profession_id')->constrained()->onDelete('cascade');
            $table->foreignId('period_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('year')->nullable()->index();
            $table->string('tran_id')->index();
            $table->boolean('is_posted')->default(0);
            $table->string('particular')->nullable();
            $table->unsignedDouble('bas', 14, 2)->nullable();
            $table->unsignedDouble('report', 14, 2)->nullable();
            $table->unsignedDouble('ato', 14, 2)->nullable();
            // $table->unsignedDouble('diff', 14, 2)->nullable();
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
        Schema::dropIfExists('reconcilation_taxes');
        Schema::dropIfExists('reconcilations');
    }
}
