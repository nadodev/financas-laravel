<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('transactions', 'due_date')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->date('due_date')->nullable()->after('date');
            });

            // Atualiza transações existentes
            DB::statement("UPDATE transactions SET due_date = date");
        }
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('due_date');
        });
    }
}; 