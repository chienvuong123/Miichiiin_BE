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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('short_description')->nullable();
            $table->integer('quantity_of_people')->nullable();
            $table->integer('price')->nullable();
            $table->integer('acreage')->nullable();
            $table->integer('id_floor')->nullable();
            $table->integer('id_hotel')->nullable();
            $table->integer('likes')->nullable();
            $table->integer('views')->nullable();
            $table->integer('status')->nullable();
            $table->integer('id_cate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
