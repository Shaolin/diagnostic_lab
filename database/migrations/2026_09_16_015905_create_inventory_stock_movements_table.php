<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_stock_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('laboratory_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('inventory_item_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('inventory_stock_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('type', [
                'receipt',
                'issue',
                'adjustment',
                'transfer_in',
                'transfer_out',
            ]);

            $table->decimal('quantity', 15, 2);

            $table->decimal('unit_cost', 15, 2)
                ->default(0);

            $table->string('reference')->nullable();

            $table->text('description')->nullable();

            $table->date('movement_date');

            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(
                ['laboratory_id', 'branch_id', 'movement_date'],
                'stock_movements_lab_branch_date_index'
            );

            $table->index(
                ['inventory_item_id', 'movement_date'],
                'stock_movements_item_date_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_stock_movements');
    }
};