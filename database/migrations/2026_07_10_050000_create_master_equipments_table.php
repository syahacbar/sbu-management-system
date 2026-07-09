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
        Schema::create('master_equipments', function (Blueprint $table): void {
            $table->id();
            $table->string('category'); // bg, bs
            $table->string('code')->unique();
            $table->string('name');
            $table->string('specification')->nullable();
            $table->string('unit')->default('Unit');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_equipments');
    }
};
