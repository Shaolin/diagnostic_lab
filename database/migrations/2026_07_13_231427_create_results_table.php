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
        Schema::create('results', function (Blueprint $table) {
            $table->id();

            // One Result per Test Request Item
            $table->foreignId('test_request_item_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            // User who uploaded the result
            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->restrictOnDelete();

            // User who verified the result
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Stored file path (private storage)
            $table->string('pdf_path');

            // Optional remarks
            $table->text('remarks')->nullable();

            // Upload & verification timestamps
            $table->timestamp('uploaded_at');
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            // Helpful indexes
            $table->index('uploaded_at');
            $table->index('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};