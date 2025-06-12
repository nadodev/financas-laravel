<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected function columnExists($table, $column)
    {
        return DB::select("
            SELECT COUNT(*) as count
            FROM information_schema.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = ?
            AND COLUMN_NAME = ?
        ", [$table, $column])[0]->count > 0;
    }

    public function up()
    {
        try {
            // Primeiro, verifica se a tabela existe
            if (!Schema::hasTable('credit_card_invoices')) {
                return;
            }

            // Remove a coluna 'month' se ela existir
            if ($this->columnExists('credit_card_invoices', 'month')) {
                DB::statement('ALTER TABLE credit_card_invoices DROP COLUMN `month`');
            }

            // Remove a coluna 'year' se ela existir
            if ($this->columnExists('credit_card_invoices', 'year')) {
                DB::statement('ALTER TABLE credit_card_invoices DROP COLUMN `year`');
            }
        } catch (\Exception $e) {
            // Log o erro se necessário
            \Log::error('Erro ao atualizar credit_card_invoices: ' . $e->getMessage());
            throw $e;
        }
    }

    public function down()
    {
        try {
            // Primeiro, verifica se a tabela existe
            if (!Schema::hasTable('credit_card_invoices')) {
                return;
            }

            // Adiciona a coluna 'month' se ela não existir
            if (!$this->columnExists('credit_card_invoices', 'month')) {
                DB::statement('ALTER TABLE credit_card_invoices ADD COLUMN `month` INTEGER');
            }

            // Adiciona a coluna 'year' se ela não existir
            if (!$this->columnExists('credit_card_invoices', 'year')) {
                DB::statement('ALTER TABLE credit_card_invoices ADD COLUMN `year` INTEGER');
            }
        } catch (\Exception $e) {
            // Log o erro se necessário
            \Log::error('Erro ao reverter credit_card_invoices: ' . $e->getMessage());
            throw $e;
        }
    }
}; 