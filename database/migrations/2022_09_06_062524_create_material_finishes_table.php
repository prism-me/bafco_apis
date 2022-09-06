<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialFinishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_finishes_pivot', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('material_id')->nullable();
            $table->bigInteger('finishes_id')->nullable();
            $table->bigInteger('finishes_value_id')->nullable();
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
        Schema::dropIfExists('material_finishes');
    }
}
