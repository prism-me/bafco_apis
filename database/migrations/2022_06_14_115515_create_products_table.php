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
            $table->text('short_description');
            $table->text('long_description')->nullable();   
            $table->text('shiping_and_return')->nullable();   
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');;
            $table->bigInteger('related_categories')->nullable();
            $table->string('promotional_images')->nullable();
            $table->bigInteger('brand');
            $table->string('album')->nullable();
            $table->text('download')->nullable();   
            $table->text('colors_materials')->nullable();   
            $table->string('type')->nullable();
            $table->string('route');
            $table->json('seo')->nullable();
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
