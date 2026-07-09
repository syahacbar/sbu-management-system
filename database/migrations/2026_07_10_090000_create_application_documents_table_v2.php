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
        Schema::dropIfExists('company_documents');
        Schema::dropIfExists('application_documents');

        Schema::create('application_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sbu_application_id')->nullable()->constrained('sbu_applications')->cascadeOnDelete();
            $table->string('document_type'); // NIB, NPWP, KTP Direktur, dll.
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->date('document_date')->nullable();
            $table->date('expired_at')->nullable();
            $table->string('status')->default('belum_ada'); // ada, belum_ada, revisi
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'sbu_application_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_documents');
    }
};
