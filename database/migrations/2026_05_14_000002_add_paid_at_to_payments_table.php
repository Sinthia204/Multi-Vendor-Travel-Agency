<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add paid_at timestamp field to payments table for tracking payment completion time
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Store the timestamp when payment was successfully processed
            $table->timestamp('paid_at')->nullable()->after('status');

            // Rename 'status' field from 'pending/success' to more clear states: unpaid, paid, failed
            // Also add reference_no as an alternative to transaction_id for simpler payments
            $table->string('reference_no')->nullable()->unique()->after('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['paid_at', 'reference_no']);
        });
    }
};
