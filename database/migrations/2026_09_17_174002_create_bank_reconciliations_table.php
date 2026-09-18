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
        Schema::create('bank_reconciliations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('laboratory_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('bank_account_id')
                ->constrained('chart_of_accounts')
                ->cascadeOnDelete();

            $table->date('statement_date');

            $table->decimal('statement_opening_balance', 15, 2);

            $table->decimal('statement_closing_balance', 15, 2);

            $table->decimal('reconciled_balance', 15, 2)
                ->default(0);

            $table->decimal('difference', 15, 2)
                ->default(0);

            $table->string('status')
                ->default('open');

            $table->text('notes')
                ->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(
                ['laboratory_id', 'branch_id'],
                'bank_reconciliations_lab_branch_index'
            );

            $table->index(
                ['bank_account_id', 'statement_date'],
                'bank_reconciliations_account_date_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_reconciliations');
    }
};