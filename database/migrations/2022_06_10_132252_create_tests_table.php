<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('public_id', 36)->nullable();
            $table->string('name')->nullable();
            $table->integer('purpose_id')->nullable();
            $table->enum('assessment_type', ['Timed Assessment', 'Deadline Based Assessment'])->nullable();
            $table->string('timezone', 100)->nullable();
            $table->float('duration', 10, 0)->nullable();
            $table->enum('status', ['Draft', 'Published'])->nullable();
            $table->text('start_message')->nullable();
            $table->text('end_message')->nullable();
            $table->integer('user_id');
            $table->timestamp('created_at')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tests');
    }
}
