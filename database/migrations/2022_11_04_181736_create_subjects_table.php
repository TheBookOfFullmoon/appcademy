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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sks');
            $table->foreignId('lecturer_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
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
        if(Schema::hasTable('student_subject')){
            Schema::table('student_subject', function (Blueprint $table){
                $table->dropForeign(['subject_id']);
            });
        }

        if(Schema::hasTable('schedule')){
            Schema::table('schedule', function (Blueprint $table){
                $table->dropForeign(['subject_id']);
            });
        }

        Schema::dropIfExists('subjects');
    }
};
