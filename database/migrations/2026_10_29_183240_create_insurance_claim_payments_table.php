
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_claim_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('laboratory_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('insurance_claim_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('amount', 12, 2);

            $table->string('payment_reference')->nullable();

            $table->timestamp('paid_at');

            $table->foreignId('received_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index(['laboratory_id', 'paid_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_claim_payments');
    }
};
