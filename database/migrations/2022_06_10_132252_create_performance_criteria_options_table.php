<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformanceCriteriaOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performance_criteria_options', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('performance_criteria_id');
            $table->integer('performance_type_id');
            $table->string('criteria');
            $table->string('formula');
            $table->float('score', 10, 0);
            $table->text('remarks')->nullable();
            $table->integer('user_id');
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
        Schema::dropIfExists('performance_criteria_options');
    }
}
