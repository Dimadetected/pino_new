<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchandising extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchandisings', function (Blueprint $table) {
            $table->id();
            $table->integer("net_id");
            $table->text("address");
            $table->date("date");
            $table->integer("user_id");
            $table->integer("product_id");
            $table->integer("balance");
            $table->decimal("price");
            $table->date("bottled_date");
            $table->text("comment");
            $table->text("photo_shelf");
            $table->text("photo_tsd");
            $table->text("photo_expiration_date");
            $table->text("photo_price");
            $table->timestamps();
        });
        Schema::create('nets', function (Blueprint $table) {
            $table->id();
            $table->text("name");
            $table->timestamps();
        });
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text("name");
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
        Schema::dropIfExists('merchandising');
        Schema::dropIfExists('nets');
        Schema::dropIfExists('products');
    }
}
