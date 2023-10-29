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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('id_hotel')->nullable();
            $table->integer('id_voucher')->nullable()->default(null);
            $table->string('slug')->unique();
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->integer('people_quantity')->nullable();
            $table->integer('total_amount')->nullable();
            $table->string('status')->default("FAIL");
            $table->string('nationality')->nullable();
            $table->string('cccd')->nullable();
            $table->string('message')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->softDeletes(); // add
            $table->string('name')->nullable();
            $table->integer('id_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
