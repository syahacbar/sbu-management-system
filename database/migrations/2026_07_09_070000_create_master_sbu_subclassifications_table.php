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
        Schema::create('master_sbu_subclassifications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('master_sbu_classification_id')
                ->constrained('master_sbu_classifications', 'id', 'sbu_subclass_class_fk')
                ->restrictOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['master_sbu_classification_id', 'is_active'], 'subclass_class_active_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_sbu_subclassifications');
    }
};
