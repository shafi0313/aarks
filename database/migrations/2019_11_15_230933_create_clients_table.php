<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('birthday')->nullable();
            $table->string('phone');
            $table->string('abn_number');
            $table->string('branch');
            $table->string('tax_file_number');
            $table->string('street_address');
            $table->string('suburb');
            $table->string('state');
            $table->string('address')->nullable();
            $table->string('post_code');
            $table->string('country');

            $table->string('director_name')->nullable();
            $table->string('director_address')->nullable();
            $table->string('agent_name')->nullable();
            $table->string('agent_address')->nullable();
            $table->string('agent_number')->nullable();
            $table->string('agent_abn_number')->nullable();
            $table->string('auditor_name')->nullable();
            $table->string('auditor_address')->nullable();
            $table->string('auditor_phone')->nullable();

            $table->boolean('is_gst_enabled')->default(false);
            $table->integer('gst_method')->default(0)->comment('0=None,1=Accrued,2=Cash');

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('logo')->default(asset('frontend/assets/images/logo/focus-icon.png'));
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('client_professions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('profession_id');
            $table->foreign('profession_id')->references('id')->on('professions')->onDelete('cascade');
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('client_service', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_service');
        Schema::dropIfExists('client_professions');
        Schema::dropIfExists('clients');
    }
}
