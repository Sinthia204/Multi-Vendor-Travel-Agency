<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->string('password')->nullable()->after('phone');
            $table->string('logo_path')->nullable()->after('password');
            $table->timestamp('approved_at')->nullable()->after('registered_at');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->rememberToken()->after('rejected_at');
        });
    }

    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn(['password', 'logo_path', 'approved_at', 'rejected_at', 'remember_token']);
        });
    }
};
