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
        // 1. Drop application_documents first to clear foreign key constraint
        Schema::dropIfExists('application_documents');

        // 2. Drop company_applications
        Schema::dropIfExists('company_applications');

        // 3. Create sbu_applications
        Schema::create('sbu_applications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('application_number');
            $table->string('application_type'); // baru, perpanjangan, perubahan
            $table->date('submission_date')->nullable();
            $table->integer('application_year');
            
            $table->foreignId('master_kbli_id')->nullable()->constrained('master_kblis')->nullOnDelete();
            $table->foreignId('master_sbu_classification_id')->nullable()->constrained('master_sbu_classifications')->nullOnDelete();
            $table->foreignId('master_sbu_subclassification_id')->nullable()->constrained('master_sbu_subclassifications')->nullOnDelete();
            $table->foreignId('master_sbu_scheme_id')->nullable()->constrained('master_sbu_schemes')->nullOnDelete();
            
            $table->string('qualification')->nullable();
            $table->string('lsbu_name')->nullable();
            $table->string('association_name')->nullable();
            $table->string('status')->default('draft'); // draft, berkas_belum_lengkap, berkas_lengkap, proses, revisi, terbit, selesai
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(false); // Satu yang aktif per perusahaan
            $table->timestamps();

            $table->index(['company_id', 'is_active']);
        });

        // 4. Recreate application_documents table pointing to sbu_applications
        Schema::create('application_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_application_id')
                ->constrained('sbu_applications')
                ->cascadeOnDelete();
            $table->string('requirement_name');
            $table->string('file_path');
            $table->string('file_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_documents');
        Schema::dropIfExists('sbu_applications');
    }
};
