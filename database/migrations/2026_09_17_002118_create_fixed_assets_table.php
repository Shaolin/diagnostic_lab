<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('laboratory_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name');
            $table->string('category')->nullable();
            $table->text('description')->nullable();

            $table->date('acquisition_date');
            $table->decimal('acquisition_cost', 15, 2);

            $table->unsignedInteger('useful_life_years')->nullable();

            $table->string('depreciation_method')
                ->default('straight_line');

            $table->decimal('accumulated_depreciation', 15, 2)
                ->default(0);

            $table->decimal('current_book_value', 15, 2);

            $table->string('status')
                ->default('active');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['laboratory_id', 'branch_id']);
            $table->index(['laboratory_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fixed_assets');
    }
};