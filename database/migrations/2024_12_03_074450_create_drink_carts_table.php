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
        Schema::create('drink_carts', function (Blueprint $table) {
            $table->id();
            $table->uuid('idDrinkCart');
            $table->foreignUuid('idDrink')->references('idDrink')->on('drinks')->onDelete('cascade');
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
        Schema::dropIfExists('drink_carts');
    }
};
