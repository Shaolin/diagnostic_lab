<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_invoice_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_invoice_id')
                ->constrained('supplier_invoices')
                ->cascadeOnDelete();

            $table->foreignId('laboratory_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('inventory_item_id')
                ->constrained('inventory_items')
                ->cascadeOnDelete();

            $table->decimal('quantity', 15, 2);

            $table->decimal('unit_cost', 15, 2);

            $table->decimal('total_amount', 15, 2);

            $table->timestamps();

            $table->index(
                ['laboratory_id', 'branch_id'],
                'invoice_items_lab_branch_index'
            );

            $table->index(
                ['supplier_invoice_id'],
                'invoice_items_invoice_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_invoice_items');
    }
};