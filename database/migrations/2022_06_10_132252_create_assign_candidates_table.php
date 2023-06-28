<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_candidates', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('assign_id');
            $table->string('name');
            $table->string('email');
            $table->integer('test_id');
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->smallInteger('assessment_type')->nullable();
            $table->tinyInteger('resume')->nullable();
            $table->tinyInteger('share')->nullable();
            $table->integer('user_id');
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('assign_candidates');
    }
}
