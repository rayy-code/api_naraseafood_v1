<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meal_carts', function (Blueprint $table) {
            $table->id();
            $table->uuid('idMealCart');
            $table->foreignUuid('idMeal')->references('idMeal')->on('meals')->onDelete('cascade');
            $table->integer('qty');
            $table->integer('totalPrice');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_carts');
    }
};
