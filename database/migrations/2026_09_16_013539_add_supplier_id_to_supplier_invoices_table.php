<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supplier_invoices', function (Blueprint $table) {
            $table->foreignId('supplier_id')
                ->nullable()
                ->after('branch_id')
                ->constrained('suppliers')
                ->nullOnDelete();

            $table->index(['laboratory_id', 'supplier_id']);
        });
    }

    public function down(): void
    {
        Schema::table('supplier_invoices', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropIndex(['laboratory_id', 'supplier_id']);
            $table->dropColumn('supplier_id');
        });
    }
};