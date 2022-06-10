<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestTakerAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_taker_answers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('taker_id');
            $table->integer('question_id');
            $table->longText('question');
            $table->string('category')->nullable();
            $table->integer('category_id');
            $table->text('option_one')->nullable();
            $table->text('option_two')->nullable();
            $table->text('option_three')->nullable();
            $table->text('option_four')->nullable();
            $table->integer('correct');
            $table->integer('marks');
            $table->integer('selected_option')->nullable();
            $table->integer('test_id');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_taker_answers');
    }
}
