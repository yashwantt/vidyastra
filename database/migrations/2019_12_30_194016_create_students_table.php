<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('st_id');
            $table->string('uid')->unique();
            $table->string('fullName');
            $table->string('gender');
            $table->date('dob');
            $table->string('father');
            $table->string('mother');
            $table->string('occupation');
            $table->double('income', 25, 2);
            $table->string('contact');
            $table->string('email');
            
            $table->string('addressLine');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('pinCode');
            
            $table->string('category');
            $table->string('subCategory');
            $table->string('religion');
            $table->string('locality');

            $table->string('bankName')->nullable();
            $table->string('bankAccount')->nullable();
            $table->string('bankIfsc')->nullable();
            $table->string('bankBranch')->nullable();
            
            $table->string('image')->nullable();
            $table->string('status');
            $table->string('cwsn');
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
        Schema::dropIfExists('students');
    }
}
