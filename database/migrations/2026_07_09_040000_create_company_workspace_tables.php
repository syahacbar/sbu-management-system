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
        Schema::create('companies', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('nib')->nullable();
            $table->string('npwp')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        foreach ($this->workspaceTables() as $tableName) {
            Schema::create($tableName, function (Blueprint $table): void {
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

                $table->index(['company_id', 'sort_order']);
                $table->index(['company_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (array_reverse($this->workspaceTables()) as $tableName) {
            Schema::dropIfExists($tableName);
        }

        Schema::dropIfExists('companies');
    }

    /**
     * @return array<int, string>
     */
    private function workspaceTables(): array
    {
        return [
            'company_directors',
            'company_pjbus',
            'company_applications',
            'company_pjtbus',
            'company_pjskbus',
            'company_experts',
            'company_equipment',
            'company_balance_entries',
            'company_documents',
            'company_archives',
        ];
    }
};
