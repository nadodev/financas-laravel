<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create a default user
        $user = User::create([
            'name' => 'Usuário Teste',
            'email' => 'teste@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create default categories
        $categories = [
            ['name' => 'Salário', 'type' => 'income', 'description' => 'Rendimentos do trabalho'],
            ['name' => 'Freelance', 'type' => 'income', 'description' => 'Trabalhos extras'],
            ['name' => 'Alimentação', 'type' => 'expense', 'description' => 'Gastos com comida'],
            ['name' => 'Transporte', 'type' => 'expense', 'description' => 'Gastos com transporte'],
            ['name' => 'Moradia', 'type' => 'expense', 'description' => 'Aluguel e contas'],
            ['name' => 'Lazer', 'type' => 'expense', 'description' => 'Entretenimento'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'type' => $category['type'],
                'description' => $category['description'],
                'user_id' => $user->id,
            ]);
        }

        // Create sample transactions
        $transactions = [
            [
                'date' => now()->subDays(5),
                'description' => 'Salário Mensal',
                'amount' => 5000.00,
                'type' => 'income',
                'category_id' => 1,
            ],
            [
                'date' => now()->subDays(3),
                'description' => 'Projeto Freelance',
                'amount' => 1200.00,
                'type' => 'income',
                'category_id' => 2,
            ],
            [
                'date' => now()->subDays(2),
                'description' => 'Supermercado',
                'amount' => 450.00,
                'type' => 'expense',
                'category_id' => 3,
            ],
            [
                'date' => now()->subDays(1),
                'description' => 'Combustível',
                'amount' => 200.00,
                'type' => 'expense',
                'category_id' => 4,
            ],
            [
                'date' => now(),
                'description' => 'Aluguel',
                'amount' => 1500.00,
                'type' => 'expense',
                'category_id' => 5,
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create([
                'date' => $transaction['date'],
                'description' => $transaction['description'],
                'amount' => $transaction['amount'],
                'type' => $transaction['type'],
                'category_id' => $transaction['category_id'],
                'user_id' => $user->id,
            ]);
        }
    }
}
