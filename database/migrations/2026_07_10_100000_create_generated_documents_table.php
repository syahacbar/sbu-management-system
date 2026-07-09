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
        Schema::dropIfExists('company_archives');

        Schema::create('generated_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sbu_application_id')->nullable()->constrained('sbu_applications')->cascadeOnDelete();
            $table->foreignId('document_template_id')->nullable()->constrained('master_document_templates')->nullOnDelete();
            $table->string('document_type'); // e.g. SBU_CERT, SPTJM, dll.
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'sbu_application_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_documents');
    }
};
