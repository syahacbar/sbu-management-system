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
        Schema::dropIfExists('company_equipment');

        Schema::create('company_equipments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sbu_application_id')->nullable()->constrained('sbu_applications')->cascadeOnDelete();
            $table->foreignId('master_equipment_id')->nullable()->constrained('master_equipments')->nullOnDelete();
            $table->string('category'); // bg, bs
            $table->string('name');
            $table->string('specification')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('unit')->default('Unit');
            $table->string('ownership_status'); // milik_sendiri, sewa, pinjam
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_equipments');
    }
};
