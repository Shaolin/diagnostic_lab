<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_stocks', function (Blueprint $table) {
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

            $table->decimal('quantity', 15, 2)
                ->default(0);

            $table->decimal('average_cost', 15, 2)
                ->default(0);

            $table->timestamps();

            $table->unique([
                'branch_id',
                'inventory_item_id'
            ]);

            $table->index([
                'laboratory_id',
                'branch_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_stocks');
    }
};