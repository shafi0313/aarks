<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDedotrJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dedotr_jobs', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(1)->comment('1=customer, 2=supplier, 3=employee, 4=personal');
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('client_account_code_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('title');
            $table->text('details');
            $table->string('is_tax');
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
        Schema::dropIfExists('dedotr_jobs');
    }
}
