<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('laboratory_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('category')->nullable();
            $table->string('unit');
            $table->text('description')->nullable();

            $table->decimal('minimum_stock', 15, 2)
                ->default(0);

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->index(['laboratory_id', 'is_active']);
            $table->index(['laboratory_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};