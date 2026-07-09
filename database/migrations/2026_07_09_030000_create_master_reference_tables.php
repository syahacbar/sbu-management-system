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
        foreach ($this->tables() as $table) {
            Schema::create($table, function (Blueprint $table): void {
                $table->id();
                $table->string('code')->nullable();
                $table->string('name');
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (array_reverse($this->tables()) as $table) {
            Schema::dropIfExists($table);
        }
    }

    /**
     * @return array<int, string>
     */
    private function tables(): array
    {
        return [
            'master_kblis',
            'master_qualifications',
            'master_lsbus',
            'master_associations',
            'master_science_fields',
            'master_bg_equipment',
            'master_bs_equipment',
            'master_balance_items',
            'master_document_templates',
        ];
    }
};
