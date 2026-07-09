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
        Schema::table('master_document_templates', function (Blueprint $table): void {
            $table->text('header_text')->nullable()->after('description');
            $table->string('logo_path')->nullable()->after('header_text');
            $table->string('signature_path')->nullable()->after('logo_path');
            $table->string('stamp_path')->nullable()->after('signature_path');
            $table->longText('template_body')->nullable()->after('stamp_path');
            $table->text('footer_text')->nullable()->after('template_body');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_document_templates', function (Blueprint $table): void {
            $table->dropColumn([
                'header_text',
                'logo_path',
                'signature_path',
                'stamp_path',
                'template_body',
                'footer_text',
            ]);
        });
    }
};
