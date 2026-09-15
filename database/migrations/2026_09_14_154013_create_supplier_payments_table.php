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
    Schema::create('supplier_payments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('supplier_invoice_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('laboratory_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('branch_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->decimal('amount', 15, 2);

        $table->enum('payment_method', [
            'Cash',
            'Transfer',
            'POS',
            'Other',
        ]);

        $table->string('payment_reference')->nullable();

        $table->dateTime('paid_at');

        $table->foreignId('paid_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->text('remarks')->nullable();

        $table->timestamps();

        $table->index(['laboratory_id', 'paid_at']);
        $table->index(['laboratory_id', 'branch_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_payments');
    }
};
