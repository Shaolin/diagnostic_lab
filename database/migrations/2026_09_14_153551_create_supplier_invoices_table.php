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
    Schema::create('supplier_invoices', function (Blueprint $table) {
        $table->id();

        $table->foreignId('laboratory_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('branch_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->string('invoice_number');
        $table->string('supplier_name');
        $table->string('supplier_phone')->nullable();
        $table->string('supplier_email')->nullable();

        $table->decimal('amount', 15, 2);
        $table->decimal('amount_paid', 15, 2)->default(0);

        $table->date('invoice_date');
        $table->date('due_date')->nullable();

        $table->string('description')->nullable();

        $table->enum('status', [
            'Unpaid',
            'Partially Paid',
            'Paid',
            'Overdue',
        ])->default('Unpaid');

        $table->foreignId('created_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamps();

        $table->index(['laboratory_id', 'status']);
        $table->index(['laboratory_id', 'branch_id']);
        $table->index(['laboratory_id', 'invoice_date']);
        $table->index(['laboratory_id', 'due_date']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_invoices');
    }
};
