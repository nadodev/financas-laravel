<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->integer('month');
            $table->integer('year');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Ensure unique budget per category, month and year for each user
            $table->unique(['category_id', 'month', 'year', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('budgets');
    }
}; 