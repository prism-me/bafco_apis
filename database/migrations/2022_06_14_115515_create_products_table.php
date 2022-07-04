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
            $table->text('short_description');
            $table->text('long_description')->nullable();   
            $table->string('featured_image');
            $table->text('shiping_and_return')->nullable();   
            $table->foreignId('category_id')->nullable();
            $table->text('related_categories')->nullable();
            $table->text('promotional_images')->nullable();
            $table->string('brand')->nullable();
            $table->text('album')->nullable();
            $table->text('download')->nullable();   
            $table->text('footrest')->nullable();   
            $table->text('headrest')->nullable();   
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
