<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Merchant
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('description');
            $table->float('price');
            $table->string('image')->default('https://cdn.pixabay.com/photo/2018/05/19/02/23/pizza-3412595_960_720.jpg');
            $table->timestamps();

            // Relationships
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
