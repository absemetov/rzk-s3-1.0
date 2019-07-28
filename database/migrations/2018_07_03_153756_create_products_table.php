<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('id')->primary();
            $table->integer('sort_id');
            $table->integer('catalog_id')->index();
            $table->string('currency_id');
            $table->string('brand_id')->nullable()->index();
            $table->integer('unit_id');
            $table->integer('warehouse_id');
            $table->boolean('instock');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('balance')->nullable();
            $table->text('image_url')->nullable();
            $table->string('often_buy')->nullable();
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
        Schema::dropIfExists('products');
    }
}
