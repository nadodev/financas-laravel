<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->enum('type', ['income', 'expense']);
            $table->enum('status', ['paid', 'pending', 'cancelled'])->default('pending');
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->foreignId('account_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('attachment')->nullable();
            
            // Campos para recorrência
            $table->boolean('recurring')->default(false);
            $table->integer('recurrence_interval')->nullable(); // em dias
            $table->date('recurrence_end_date')->nullable();
            $table->date('next_recurrence_date')->nullable();
            $table->foreignId('parent_id')->nullable()->references('id')->on('transactions')->onDelete('cascade');
            
            // Campos para parcelamento
            $table->boolean('installment')->default(false);
            $table->integer('total_installments')->nullable();
            $table->integer('current_installment')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}; 