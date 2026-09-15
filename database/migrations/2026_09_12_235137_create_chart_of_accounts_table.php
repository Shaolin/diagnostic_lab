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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('laboratory_id')
                ->constrained('laboratories')
                ->cascadeOnDelete();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('chart_of_accounts')
                ->nullOnDelete();

            $table->string('code', 20);
            $table->string('name');

            $table->enum('type', [
                'asset',
                'liability',
                'equity',
                'income',
                'expense',
            ]);

            $table->boolean('is_system')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->unique(['laboratory_id', 'code']);
            $table->index(['laboratory_id', 'type']);
            $table->index(['laboratory_id', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};