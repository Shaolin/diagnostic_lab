
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_claims', function (Blueprint $table) {
            $table->id();

            $table->foreignId('laboratory_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('test_request_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('insurance_company');
            $table->string('membership_number')->nullable();

            $table->decimal('covered_amount', 12, 2);
            $table->decimal('patient_contribution', 12, 2)
                ->default(0);

            $table->enum('status', [
                'Pending',
                'Submitted',
                'Partially Paid',
                'Paid',
                'Rejected',
            ])->default('Pending');

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index(['laboratory_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_claims');
    }
};
