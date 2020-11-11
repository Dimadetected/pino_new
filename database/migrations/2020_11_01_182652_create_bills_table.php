<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->text('text')->nullable();
            $table->foreignId('bill_type_id')->nullable();
            $table->foreignId('file_id')->nullable();
            $table->foreignId('bill_status_id')->nullable();
            $table->foreignId('bill_answer_id')->nullable();
            $table->foreignId('status')->default(1);
            $table->integer('steps')->nullable();
            $table->foreignId('user_role_id')->nullable();
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
        Schema::dropIfExists('bills');
    }
}
