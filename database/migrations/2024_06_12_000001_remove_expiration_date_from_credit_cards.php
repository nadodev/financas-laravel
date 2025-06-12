<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('credit_cards', function (Blueprint $table) {
            $table->dropColumn('expiration_date');
        });
    }

    public function down()
    {
        Schema::table('credit_cards', function (Blueprint $table) {
            $table->date('expiration_date')->after('number');
        });
    }
}; 