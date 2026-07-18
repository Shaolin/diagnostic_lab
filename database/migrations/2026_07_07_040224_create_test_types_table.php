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
        Schema::create('test_types', function (Blueprint $table) {

            $table->id();

            // Laboratory
            $table->foreignId('laboratory_id')
                ->constrained()
                ->cascadeOnDelete();

            // Unique test code per laboratory
            $table->string('code');

            // Test details
            $table->string('name');
            $table->string('category')->nullable();
            $table->text('description')->nullable();

            // Pricing
            $table->decimal('default_price', 12, 2);

            // Turnaround time in hours
            $table->unsignedInteger('estimated_turnaround_hours')->nullable();

            // Active / Inactive
            $table->boolean('is_active')->default(true);

            // Audit
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            // Code must be unique within a laboratory
            $table->unique(['laboratory_id', 'code']);

            // Test name must be unique within a laboratory
            $table->unique(['laboratory_id', 'name']);

            // Frequently searched fields
            $table->index('name');
            $table->index('category');
            $table->index('is_active');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_types');
    }
};