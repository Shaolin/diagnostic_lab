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
        Schema::create('test_requests', function (Blueprint $table) {

            $table->id();

            // Laboratory
            $table->foreignId('laboratory_id')
                ->constrained()
                ->cascadeOnDelete();

            // Patient
            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            // Public Tracking Code
            $table->string('tracking_code')->unique();

            // Total Cost
            $table->decimal('total_amount', 12, 2)->default(0);

            // Optional remarks
            $table->text('remarks')->nullable();

            // Overall Request Status
            $table->enum('overall_status', [
                'Pending',
                'In Progress',
                'Completed',
                'Partially Completed'
            ])->default('Pending');

            // Audit
            $table->foreignId('requested_by')
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

            $table->index('laboratory_id');
            $table->index('patient_id');
            $table->index('tracking_code');
            $table->index('overall_status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_requests');
    }
};