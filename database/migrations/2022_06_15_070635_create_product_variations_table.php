<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable();
            $table->string('code')->nullable();
            $table->string('lc_code')->nullable();
            $table->string('cbm')->nullable();
            $table->boolean('in_stock')->default(1);
            $table->string('upper_price')->nullable();
            $table->string('lower_price')->nullable();
            $table->string('height')->nullable();
            $table->string('depth')->nullable();
            $table->string('width')->nullable();
            $table->text('description')->nullable();
            $table->text('images')->nullable();
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
        Schema::dropIfExists('product_variations');
    }
}
