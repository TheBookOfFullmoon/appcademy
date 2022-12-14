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
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('birthday');
            $table->string('birth_place');
            $table->string('address');
            $table->string('gender');
            $table->string('phone');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
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
        if(Schema::hasTable('subjects')){
            Schema::table('subjects', function (Blueprint $table){
                $table->dropForeign(['lecturer_id']);
            });
        }

        Schema::dropIfExists('lecturers');
    }
};
