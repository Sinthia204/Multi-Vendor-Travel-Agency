<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds trade license number and business document fields for agency verification.
     */
    public function up(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            // Trade license number - nullable string for optional verification
            $table->string('trade_license_number')->nullable()->after('phone');

            // Business document path - stored as file path, not the actual file
            $table->string('business_document')->nullable()->after('trade_license_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn(['trade_license_number', 'business_document']);
        });
    }
};
