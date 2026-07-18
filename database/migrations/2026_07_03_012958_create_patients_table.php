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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('laboratory_id')
                ->constrained()
                ->cascadeOnDelete();

            // Unique patient number per laboratory
            $table->string('patient_number', 20);

            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('other_names')->nullable();

            $table->enum('gender', ['Male', 'Female']);

            $table->date('date_of_birth')->nullable();

            $table->string('phone');
            $table->string('email')->nullable();

            $table->text('address')->nullable();

            // Medical Information
            $table->string('blood_group', 5)->nullable();
            $table->string('genotype', 5)->nullable();

            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();

            // Additional Notes
            $table->text('notes')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            // Audit Trail
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Indexes
            $table->unique(['laboratory_id', 'patient_number']);

            $table->index('first_name');
            $table->index('last_name');
            $table->index('phone');
            $table->index('is_active');
            $table->index('laboratory_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};