<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('credit_card_invoices')) {
            return;
        }

        if (Schema::hasColumn('credit_card_invoices', 'month')) {
            Schema::table('credit_card_invoices', function (Blueprint $table) {
                $table->dropColumn('month');
            });
        }

        if (Schema::hasColumn('credit_card_invoices', 'year')) {
            Schema::table('credit_card_invoices', function (Blueprint $table) {
                $table->dropColumn('year');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('credit_card_invoices')) {
            return;
        }

        if (!Schema::hasColumn('credit_card_invoices', 'month')) {
            Schema::table('credit_card_invoices', function (Blueprint $table) {
                $table->integer('month');
            });
        }

        if (!Schema::hasColumn('credit_card_invoices', 'year')) {
            Schema::table('credit_card_invoices', function (Blueprint $table) {
                $table->integer('year');
            });
        }
    }
}; 