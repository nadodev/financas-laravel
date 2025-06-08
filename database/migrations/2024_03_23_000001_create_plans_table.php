<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->text('description');
            $table->json('features');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Adicionar coluna plan_id na tabela users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn('plan_id');
        });
        
        Schema::dropIfExists('plans');
    }
}; 