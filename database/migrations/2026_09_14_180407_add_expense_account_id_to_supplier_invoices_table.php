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
    Schema::table('supplier_invoices', function (Blueprint $table) {
        $table->foreignId('expense_account_id')
            ->nullable()
            ->after('branch_id')
            ->constrained('chart_of_accounts')
            ->nullOnDelete();

        $table->index(['laboratory_id', 'expense_account_id']);
    });
}

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::table('supplier_invoices', function (Blueprint $table) {
        $table->dropForeign(['expense_account_id']);
        $table->dropIndex(['laboratory_id', 'expense_account_id']);
        $table->dropColumn('expense_account_id');
    });
}
};
