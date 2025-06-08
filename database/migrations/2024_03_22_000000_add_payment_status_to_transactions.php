<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->after('type'); // pending, paid, overdue
            $table->date('payment_date')->nullable()->after('payment_status');
            $table->foreignId('paid_with_account_id')->nullable()->after('payment_date')
                ->constrained('accounts')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['paid_with_account_id']);
            $table->dropColumn(['payment_status', 'payment_date', 'paid_with_account_id']);
        });
    }
}; 