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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reserv_name', 255);
            $table->date('reserv_start');
            $table->date('reserv_end');
            $table->enum('reserv_status', ['pending', 'confirmed', 'canceled']);
            $table->unsignedBigInteger('user_id');   
            $table->unsignedBigInteger('space_id');  
            $table->timestamps();  
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('space_id')->references('id')->on('spaces')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
