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
        Schema::create('master_sbu_schemes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('master_kbli_id')->constrained('master_kblis')->restrictOnDelete();
            $table->foreignId('master_sbu_classification_id')->constrained('master_sbu_classifications')->restrictOnDelete();
            $table->foreignId('master_sbu_subclassification_id')->constrained('master_sbu_subclassifications')->restrictOnDelete();
            $table->string('scheme_code')->unique();
            $table->string('scheme_name');
            $table->string('qualification');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['qualification', 'is_active']);
            $table->index(['master_kbli_id', 'master_sbu_subclassification_id'], 'schemes_kbli_subclass_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_sbu_schemes');
    }
};
