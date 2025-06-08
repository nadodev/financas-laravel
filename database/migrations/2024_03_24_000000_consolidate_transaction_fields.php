<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Remove foreign keys se existirem
            if (Schema::hasColumn('transactions', 'credit_card_id')) {
                $table->dropForeign(['credit_card_id']);
            }
            if (Schema::hasColumn('transactions', 'credit_card_invoice_id')) {
                $table->dropForeign(['credit_card_invoice_id']);
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            // Remove colunas antigas se existirem
            $columns = [
                'is_recurring',
                'recurrence_type',
                'is_installment',
                'installment_amount',
                'parent_transaction_id',
                'credit_card_id',
                'credit_card_invoice_id',
                'notes',
                'attachments'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('transactions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            // Adiciona novos campos se não existirem
            if (!Schema::hasColumn('transactions', 'recurring')) {
                $table->boolean('recurring')->default(false);
            }
            if (!Schema::hasColumn('transactions', 'recurrence_interval')) {
                $table->integer('recurrence_interval')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'recurrence_end_date')) {
                $table->date('recurrence_end_date')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'next_recurrence_date')) {
                $table->date('next_recurrence_date')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'installment')) {
                $table->boolean('installment')->default(false);
            }
            if (!Schema::hasColumn('transactions', 'total_installments')) {
                $table->integer('total_installments')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'current_installment')) {
                $table->integer('current_installment')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'status')) {
                $table->string('status')->default('pending');
            }
            if (!Schema::hasColumn('transactions', 'attachment')) {
                $table->string('attachment')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->constrained('transactions')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Remove foreign key se existir
            if (Schema::hasColumn('transactions', 'parent_id')) {
                $table->dropForeign(['parent_id']);
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            // Remove novos campos
            $columns = [
                'recurring',
                'recurrence_interval',
                'recurrence_end_date',
                'next_recurrence_date',
                'installment',
                'total_installments',
                'current_installment',
                'status',
                'attachment',
                'parent_id'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('transactions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            // Restaura campos antigos
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_type')->nullable();
            $table->boolean('is_installment')->default(false);
            $table->decimal('installment_amount', 10, 2)->nullable();
            $table->unsignedBigInteger('parent_transaction_id')->nullable();
            $table->unsignedBigInteger('credit_card_id')->nullable();
            $table->unsignedBigInteger('credit_card_invoice_id')->nullable();
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();

            // Restaura foreign keys
            $table->foreign('credit_card_id')->references('id')->on('credit_cards')->onDelete('cascade');
            $table->foreign('credit_card_invoice_id')->references('id')->on('credit_card_invoices')->onDelete('cascade');
        });
    }
}; 