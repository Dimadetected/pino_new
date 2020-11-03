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
            $table->foreignId('status')->nullable();
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
