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
        Schema::table('companies', function (Blueprint $table): void {
            // Drop old columns if they exist
            if (Schema::hasColumn('companies', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('companies', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('companies', 'description')) {
                $table->dropColumn('description');
            }

            // Add new columns
            $table->string('npwp_clean')->nullable()->after('npwp');
            $table->string('business_type')->nullable()->after('phone');
            $table->string('qualification')->nullable()->after('business_type');
            $table->string('province')->nullable()->after('qualification');
            $table->string('city')->nullable()->after('province');
            $table->string('district')->nullable()->after('city');
            $table->string('village')->nullable()->after('district');
            $table->string('rt_rw')->nullable()->after('village');
            $table->string('street')->nullable()->after('rt_rw');
            $table->string('signing_place')->nullable()->after('street');
            $table->text('notes')->nullable()->after('signing_place');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table): void {
            $table->text('address')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('address');
            $table->text('description')->nullable()->after('is_active');
            $table->dropColumn([
                'npwp_clean',
                'business_type',
                'qualification',
                'province',
                'city',
                'district',
                'village',
                'rt_rw',
                'street',
                'signing_place',
                'notes',
            ]);
        });
    }
};
