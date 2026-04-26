<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('agency_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->foreignId('package_id')->nullable()->after('agency_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['agency_id']);
            $table->dropForeign(['package_id']);
            $table->dropColumn(['agency_id', 'package_id']);
        });
    }
};
