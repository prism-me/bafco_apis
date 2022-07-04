<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationPivotVariationValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variation_pivot_variation_values', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_variation_id');
            $table->bigInteger('variation_id');
            $table->bigInteger('variation_value_id');
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
        Schema::dropIfExists('product_variation_pivot_variation_values');
    }
}
