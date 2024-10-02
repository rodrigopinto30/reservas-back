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
        Schema::create('spaces', function (Blueprint $table) {
            $table->id();
            $table->string('space_name', 100)->unique();
            $table->integer('space_capacity');
            $table->date('space_avail_from');
            $table->date('space_avail_to');
            $table->integer('space_price_hour');
            $table->timestamp('space_last_used_at')->nullable();
            $table->timestamp('space_expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spaces');
    }
};
