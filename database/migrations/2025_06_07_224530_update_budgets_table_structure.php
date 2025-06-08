<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Criar tabela temporária com a nova estrutura
        Schema::create('budgets_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('recurrence', ['monthly', 'quarterly', 'yearly'])->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'category_id', 'start_date', 'end_date'], 'unique_budget_period');
        });

        // 2. Migrar dados da tabela antiga para a nova
        $budgets = DB::table('budgets')->get();
        foreach ($budgets as $budget) {
            $startDate = Carbon::createFromDate($budget->year, $budget->month, 1);
            $endDate = $startDate->copy()->endOfMonth();

            DB::table('budgets_new')->insert([
                'id' => $budget->id,
                'user_id' => $budget->user_id,
                'category_id' => $budget->category_id,
                'amount' => $budget->amount,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'description' => $budget->notes,
                'created_at' => $budget->created_at,
                'updated_at' => $budget->updated_at
            ]);
        }

        // 3. Excluir tabela antiga
        Schema::drop('budgets');

        // 4. Renomear tabela nova
        Schema::rename('budgets_new', 'budgets');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Criar tabela temporária com a estrutura antiga
        Schema::create('budgets_old', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->integer('month');
            $table->integer('year');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['category_id', 'month', 'year', 'user_id']);
        });

        // 2. Migrar dados da tabela nova para a antiga
        $budgets = DB::table('budgets')->get();
        foreach ($budgets as $budget) {
            if ($budget->start_date) {
                $date = Carbon::parse($budget->start_date);
                
                DB::table('budgets_old')->insert([
                    'id' => $budget->id,
                    'user_id' => $budget->user_id,
                    'category_id' => $budget->category_id,
                    'amount' => $budget->amount,
                    'month' => $date->month,
                    'year' => $date->year,
                    'notes' => $budget->description,
                    'created_at' => $budget->created_at,
                    'updated_at' => $budget->updated_at
                ]);
            }
        }

        // 3. Excluir tabela nova
        Schema::drop('budgets');

        // 4. Renomear tabela antiga
        Schema::rename('budgets_old', 'budgets');
    }
};
