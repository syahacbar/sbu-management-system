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
        // Create company_persons table
        Schema::create('company_persons', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // direktur, pjbu
            $table->string('name');
            $table->string('nik', 16);
            $table->string('birthplace')->nullable();
            $table->string('npwp')->nullable();
            $table->string('npwp_clean')->nullable();
            $table->string('email')->nullable();
            $table->string('position');
            $table->boolean('is_main')->default(false);
            $table->timestamps();

            $table->index(['company_id', 'type']);
            $table->index(['company_id', 'is_main']);
        });

        // Drop old tables
        Schema::dropIfExists('company_directors');
        Schema::dropIfExists('company_pjbus');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_persons');

        // Recreate dropped tables for rollback support
        Schema::create('company_directors', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('code')->nullable();
            $table->string('name');
            $table->string('status')->default('draft');
            $table->date('record_date')->nullable();
            $table->decimal('amount', 18, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('company_pjbus', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('code')->nullable();
            $table->string('name');
            $table->string('status')->default('draft');
            $table->date('record_date')->nullable();
            $table->decimal('amount', 18, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
};
