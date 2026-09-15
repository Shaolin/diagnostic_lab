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
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();

        $table->foreignId('laboratory_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('branch_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->foreignId('expense_account_id')
            ->constrained('chart_of_accounts')
            ->restrictOnDelete();

        $table->decimal('amount', 15, 2);

        $table->string('payment_method', 50);

        $table->string('payment_reference')->nullable();

        $table->date('expense_date');

        $table->text('description')->nullable();

        $table->foreignId('created_by')
            ->constrained('users')
            ->restrictOnDelete();

        $table->timestamps();

        $table->index(['laboratory_id', 'expense_date']);
        $table->index(['laboratory_id', 'branch_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
