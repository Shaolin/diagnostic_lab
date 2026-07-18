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
        Schema::create('laboratories', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('name');
            $table->string('subdomain')->unique();

            // Branding
            $table->string('logo')->nullable();

            // Contact Information
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            // Location
            $table->string('country')->nullable();
            $table->string('timezone')->nullable();

            // Currency
            $table->string('currency_code', 10)->default('NGN');
            $table->string('currency_symbol', 10)->default('₦');

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Helpful indexes for future lookups
            $table->index('name');
            $table->index('country');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratories');
    }
};