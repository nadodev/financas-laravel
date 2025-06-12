<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('credit_card_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_card_id')->constrained()->onDelete('cascade');
            $table->integer('reference_month');
            $table->integer('year');
            $table->integer('reference_year');
            $table->date('closing_date');
            $table->date('due_date');
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('status')->default('open'); // open, closed, paid, overdue
            $table->timestamps();

            // Uma fatura por mês por cartão
            $table->unique(['credit_card_id', 'reference_month', 'reference_year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('credit_card_invoices');
    }
}; 