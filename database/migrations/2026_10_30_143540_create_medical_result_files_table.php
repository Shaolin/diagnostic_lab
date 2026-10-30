
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_result_files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('test_request_item_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('file_path');
            $table->string('original_name');
            $table->string('file_type'); // X-ray, EEG, Ultrasound, Other
            $table->string('mime_type');
            $table->text('description')->nullable();

            $table->timestamps();

            $table->index('file_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_result_files');
    }
};
