<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id');
            $table->string('ref_no')->unique();
            $table->unsignedBigInteger('appointment_user_id');
            $table->foreign('appointment_user_id')->references('user_id')->on('users');

            $table->unsignedBigInteger('training_center_id');
            $table->foreign('training_center_id')->references('training_center_id')->on('training_centers');

            $table->date('app_date')->nullable();
            $table->time('app_time_from')->nullable();
            $table->time('app_time_to')->nullable();

            $table->text('remarks')->nullable();
            $table->tinyInteger('app_status')->default(0);
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
        Schema::dropIfExists('appointments');
    }
}
