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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->date('date');
            $table->date('due_date');
            $table->float('discount')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade'); 
            $table->unsignedBigInteger('taxe_id')->nullable();
            $table->foreign('taxe_id')->references('id')->on('taxes')->onDelete('cascade')->nullable(); 
            //0: Invoice, 1: Estimate, 2: Purchase Orders
            $table->tinyInteger('type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factures');
    }
};
