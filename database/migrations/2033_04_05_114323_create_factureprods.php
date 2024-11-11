<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('factureprods', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('quantity');
            $table->float('discount')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade'); 
            $table->unsignedBigInteger('factures_id');
            $table->foreign('factures_id')->references('id')->on('factures')->onDelete('cascade'); 
            $table->unsignedBigInteger('taxe_id')->nullable();
            $table->foreign('taxe_id')->references('id')->on('taxes')->onDelete('cascade')->nullable(); 
            $table->unique(['product_id', 'factures_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factureprods');
    }
};
