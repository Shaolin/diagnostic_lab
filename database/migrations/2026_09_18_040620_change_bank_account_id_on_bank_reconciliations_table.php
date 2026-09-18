<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_reconciliations', function (Blueprint $table) {
            $table->dropForeign(['bank_account_id']);

            $table->foreign('bank_account_id')
                ->references('id')
                ->on('bank_accounts')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bank_reconciliations', function (Blueprint $table) {
            $table->dropForeign(['bank_account_id']);

            $table->foreign('bank_account_id')
                ->references('id')
                ->on('chart_of_accounts')
                ->cascadeOnDelete();
        });
    }
};