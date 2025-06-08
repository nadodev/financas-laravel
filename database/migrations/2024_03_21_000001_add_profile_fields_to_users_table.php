<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cpf', 14)->nullable()->after('email');
            $table->string('phone', 15)->nullable()->after('cpf');
            $table->string('address')->nullable()->after('phone');
            $table->string('address_number', 10)->nullable()->after('address');
            $table->string('complement')->nullable()->after('address_number');
            $table->string('neighborhood')->nullable()->after('complement');
            $table->string('city')->nullable()->after('neighborhood');
            $table->string('state', 2)->nullable()->after('city');
            $table->string('zip_code', 9)->nullable()->after('state');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'cpf',
                'phone',
                'address',
                'address_number',
                'complement',
                'neighborhood',
                'city',
                'state',
                'zip_code'
            ]);
        });
    }
}; 