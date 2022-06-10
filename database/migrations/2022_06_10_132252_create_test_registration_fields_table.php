<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestRegistrationFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_registration_fields', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('test_id');
            $table->string('field');
            $table->string('type');
            $table->tinyInteger('required')->default(0);
            $table->integer('user_id');
            $table->tinyInteger('allow')->nullable();
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
        Schema::dropIfExists('test_registration_fields');
    }
}
