<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions_bank', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->text('title');
            $table->integer('marks');
            $table->text('correct');
            $table->string('type', 50);
            $table->text('answers_justification')->nullable();
            $table->integer('user_id');
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
        Schema::dropIfExists('questions_bank');
    }
}