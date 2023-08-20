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
            $table->string('name');
            $table->string('description');
            $table->string('short_description');
            $table->integer('quantity_of_people');
            $table->integer('price');
            $table->integer('acreage');
            $table->integer('id_floor');
            $table->integer('id_hotel');
            $table->integer('likes');
            $table->integer('views');
            $table->integer('status');
            $table->integer('id_cate');
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
