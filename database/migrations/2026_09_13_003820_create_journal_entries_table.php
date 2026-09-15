<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('laboratory_id')
                ->constrained('laboratories')
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained('branches')
                ->nullOnDelete();

            $table->date('entry_date');

            $table->string('reference', 100)->nullable();

            $table->string('description');

            $table->string('source_type', 50)->nullable();

            $table->unsignedBigInteger('source_id')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->enum('status', [
                'draft',
                'posted',
            ])->default('draft');

            $table->timestamp('posted_at')->nullable();

            $table->timestamps();

            $table->index(['laboratory_id', 'entry_date']);
            $table->index(['laboratory_id', 'branch_id']);
            $table->index(['source_type', 'source_id']);
            $table->index(['laboratory_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};