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
        Schema::dropIfExists('company_balance_entries');

        Schema::create('financial_statements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sbu_application_id')->nullable()->constrained('sbu_applications')->cascadeOnDelete();
            $table->integer('year_one');
            $table->integer('year_two');
            $table->date('statement_date');
            $table->timestamps();

            $table->index(['company_id', 'sbu_application_id']);
        });

        Schema::create('financial_statement_values', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('financial_statement_id')->constrained('financial_statements')->cascadeOnDelete();
            $table->foreignId('master_financial_item_id')->constrained('master_financial_items')->cascadeOnDelete();
            $table->decimal('year_one_amount', 20, 2)->default(0);
            $table->decimal('year_two_amount', 20, 2)->default(0);
            $table->timestamps();

            $table->index(['financial_statement_id', 'master_financial_item_id'], 'statement_item_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_statement_values');
        Schema::dropIfExists('financial_statements');
    }
};
