<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestCartCalculationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_cart_calculations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('coupon')->nullable();
            $table->bigInteger('discounted_price')->nullable();
            $table->bigInteger('shipping_charges')->nullable();
            $table->bigInteger('total')->nullable();
            $table->bigInteger('sub_total')->nullable();
            $table->decimal('decimal_amount')->nullable();
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
        Schema::dropIfExists('guest_cart_calculations');
    }
}
