<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('credit_card_invoices', function (Blueprint $table) {
            // Remove colunas antigas
            $table->dropColumn(['month', 'year']);
            
            // Adiciona novas colunas
            $table->integer('reference_month')->after('credit_card_id');
            $table->integer('reference_year')->after('reference_month');
        });
    }

    public function down()
    {
        Schema::table('credit_card_invoices', function (Blueprint $table) {
            // Remove novas colunas
            $table->dropColumn(['reference_month', 'reference_year']);
            
            // Restaura colunas antigas
            $table->integer('month')->after('credit_card_id');
            $table->integer('year')->after('month');
        });
    }
}; 