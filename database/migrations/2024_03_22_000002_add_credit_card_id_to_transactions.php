<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('credit_card_id')->nullable()->after('account_id')
                  ->constrained('credit_cards')
                  ->onDelete('restrict');
            
            $table->foreignId('credit_card_invoice_id')->nullable()->after('credit_card_id')
                  ->constrained('credit_card_invoices')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['credit_card_invoice_id']);
            $table->dropForeign(['credit_card_id']);
            $table->dropColumn(['credit_card_invoice_id', 'credit_card_id']);
        });
    }
}; 