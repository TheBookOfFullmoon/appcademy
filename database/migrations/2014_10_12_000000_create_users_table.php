<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role');
            $table->rememberToken();
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
        if(Schema::hasTable('students')){
            Schema::table('students', function (Blueprint $table){
               $table->dropForeign(['user_id']);
            });
        }

        if(Schema::hasTable('lecturers')){
            Schema::table('lecturers', function (Blueprint $table){
                $table->dropForeign(['user_id']);
            });
        }
        Schema::dropIfExists('users');
    }
};
