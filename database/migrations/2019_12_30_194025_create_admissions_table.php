<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->bigIncrements('ad_id');
            $table->dateTime('doa');
            $table->string('session');
            $table->string('type');
            $table->string('admsNumber');
            $table->string('class');
            $table->string('section');
            $table->string('subject');
            $table->string('subjectSections');
            
            $table->bigInteger('student_id')->unsigned();
            $table->foreign('student_id')->references('st_id')->on('students')->onDelete('cascade');
            
            $table->unique(['session','student_id']);
            
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
        Schema::dropIfExists('admissions');
    }
}
