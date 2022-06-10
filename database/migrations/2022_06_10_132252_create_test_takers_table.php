<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestTakersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_takers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('key');
            $table->string('name');
            $table->string('email');
            $table->dateTime('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('mobile', 100)->nullable();
            $table->longText('avatar')->nullable();
            $table->longText('id_card')->nullable();
            $table->integer('test_id');
            $table->text('test_name');
            $table->integer('total_questions');
            $table->integer('total_marks');
            $table->integer('user_id');
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('test_takers');
    }
}
