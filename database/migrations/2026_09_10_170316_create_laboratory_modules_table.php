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
        Schema::create('laboratory_modules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('laboratory_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('module');
            $table->boolean('enabled')->default(false);

            $table->timestamps();

            $table->unique(['laboratory_id', 'module']);
            $table->index('module');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratory_modules');
    }
};