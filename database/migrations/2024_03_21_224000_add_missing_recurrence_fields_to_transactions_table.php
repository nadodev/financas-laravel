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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('recurrence_interval')->nullable()->after('is_recurring');
            $table->date('recurrence_end_date')->nullable()->after('recurrence_interval');
            $table->integer('total_installments')->nullable()->after('current_installment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'recurrence_interval',
                'recurrence_end_date',
                'total_installments'
            ]);
        });
    }
}; 