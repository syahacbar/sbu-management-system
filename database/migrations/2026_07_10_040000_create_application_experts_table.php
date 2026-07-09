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
        Schema::create('application_experts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sbu_application_id')->constrained('sbu_applications')->cascadeOnDelete();
            $table->string('expert_type'); // pjtbu, pjskbu, tenaga_ahli
            $table->string('name');
            $table->string('nik');
            $table->string('npwp')->nullable();
            $table->string('npwp_clean')->nullable();
            $table->string('skk_registration_number')->nullable();
            $table->string('skk_classification')->nullable();
            $table->string('skk_subclassification')->nullable();
            $table->string('skk_qualification')->nullable();
            $table->string('skk_level')->nullable();
            $table->date('skk_issued_at')->nullable();
            $table->date('skk_expired_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['sbu_application_id', 'expert_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_experts');
    }
};
