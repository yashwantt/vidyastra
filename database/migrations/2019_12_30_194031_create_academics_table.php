<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academics', function (Blueprint $table) {
            $table->bigIncrements('ac_id');
            $table->text('academicData');
            
            $table->bigInteger('admission_id')->unsigned();
            $table->foreign('admission_id')->references('ad_id')->on('admissions')->onDelete('cascade');
            
            $table->bigInteger('student_id')->unsigned();
            $table->foreign('student_id')->references('st_id')->on('students')->onDelete('cascade');
            
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            
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
        Schema::dropIfExists('academics');
    }
}
