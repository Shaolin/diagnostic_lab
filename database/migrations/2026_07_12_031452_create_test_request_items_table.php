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
        Schema::create('test_request_items', function (Blueprint $table) {

            $table->id();

            // Parent Request
            $table->foreignId('test_request_id')
                ->constrained()
                ->cascadeOnDelete();

            // Test Type
            $table->foreignId('test_type_id')
                ->constrained()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Historical Snapshot
            |--------------------------------------------------------------------------
            */

            // Preserve test name even if renamed later
            $table->string('test_name');

            // Preserve historical price
            $table->decimal('price', 12, 2);

            /*
            |--------------------------------------------------------------------------
            | Workflow Statuses
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'Pending',
                'In Progress',
                'Completed'
            ])->default('Pending');

            $table->enum('sample_status', [
                'Pending',
                'Collected'
            ])->default('Pending');

            $table->enum('result_status', [
                'Not Ready',
                'Ready',
                'Sent'
            ])->default('Not Ready');

            /*
            |--------------------------------------------------------------------------
            | Future Result Upload
            |--------------------------------------------------------------------------
            */

            $table->string('result_pdf')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Completion Timestamp
            |--------------------------------------------------------------------------
            */

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('test_request_id');
            $table->index('test_type_id');
            $table->index('status');
            $table->index('sample_status');
            $table->index('result_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_request_items');
    }
};