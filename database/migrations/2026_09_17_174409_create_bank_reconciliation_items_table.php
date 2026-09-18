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
        Schema::create('bank_reconciliation_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bank_reconciliation_id')
                ->constrained('bank_reconciliations')
                ->cascadeOnDelete();

            $table->foreignId('journal_entry_line_id')
                ->constrained('journal_entry_lines')
                ->cascadeOnDelete();

            $table->date('reconciled_date');

            $table->timestamps();

            $table->unique(
                ['bank_reconciliation_id', 'journal_entry_line_id'],
                'bank_reconciliation_items_unique'
            );

            $table->index(
                ['journal_entry_line_id'],
                'bank_reconciliation_items_line_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_reconciliation_items');
    }
};