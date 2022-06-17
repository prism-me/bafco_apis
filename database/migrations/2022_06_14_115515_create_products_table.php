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
            $table->string('name', 255);
            $table->string('featured_image');
            $table->json('slider_images');
            $table->text('short_description');
            $table->longText('long_description')->nullable();   
            $table->text('dimentions')->nullable();   
            $table->text('resources')->nullable();   
            $table->text('colors_materials')->nullable();   
            $table->string('promotional_images')->nullable();
            $table->bigInteger('category_id');
            $table->bigInteger('brand_id');
            $table->string('album');
            $table->string('type')->nullable();
            $table->string('route');
            $table->json('seo');
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
