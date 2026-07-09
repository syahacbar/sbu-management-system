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
        Schema::table('company_applications', function (Blueprint $table): void {
            $table->foreignId('master_kbli_id')
                ->nullable()
                ->after('company_id')
                ->constrained('master_kblis')
                ->nullOnDelete();

            $table->foreignId('master_sbu_classification_id')
                ->nullable()
                ->after('master_kbli_id')
                ->constrained('master_sbu_classifications', 'id', 'app_class_fk')
                ->nullOnDelete();

            $table->foreignId('master_sbu_subclassification_id')
                ->nullable()
                ->after('master_sbu_classification_id')
                ->constrained('master_sbu_subclassifications', 'id', 'app_subclass_fk')
                ->nullOnDelete();

            $table->foreignId('master_sbu_scheme_id')
                ->nullable()
                ->after('master_sbu_subclassification_id')
                ->constrained('master_sbu_schemes', 'id', 'app_scheme_fk')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_applications', function (Blueprint $table): void {
            $table->dropForeign('app_scheme_fk');
            $table->dropForeign('app_subclass_fk');
            $table->dropForeign('app_class_fk');
            $table->dropForeign(['master_kbli_id']);

            $table->dropColumn([
                'master_kbli_id',
                'master_sbu_classification_id',
                'master_sbu_subclassification_id',
                'master_sbu_scheme_id',
            ]);
        });
    }
};
