<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $budgets = Budget::with('category')
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->where('user_id', auth()->id())
            ->get();

        $totalBudget = $budgets->sum('amount');
        $totalSpent = $budgets->sum('spent');
        $remainingBudget = $totalBudget - $totalSpent;

        return view('budgets.index', compact('budgets', 'totalBudget', 'totalSpent', 'remainingBudget', 'currentMonth', 'currentYear'));
    }

    public function create()
    {
        $categories = Category::where('type', 'expense')
            ->where('user_id', auth()->id())
            ->get();
            
        return view('budgets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000',
            'notes' => 'nullable|string'
        ]);

        $validated['user_id'] = auth()->id();

        // Check for existing budget
        $exists = Budget::where('category_id', $validated['category_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->where('user_id', auth()->id())
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['category_id' => 'Já existe um orçamento para esta categoria neste mês.']);
        }

        Budget::create($validated);

        return redirect()->route('budgets.index')
            ->with('success', 'Orçamento criado com sucesso!');
    }

    public function edit(Budget $budget)
    {
        $categories = Category::where('type', 'expense')
            ->where('user_id', auth()->id())
            ->get();
            
        return view('budgets.edit', compact('budget', 'categories'));
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000',
            'notes' => 'nullable|string'
        ]);

        // Check for existing budget (excluding current one)
        $exists = Budget::where('category_id', $validated['category_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->where('user_id', auth()->id())
            ->where('id', '!=', $budget->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['category_id' => 'Já existe um orçamento para esta categoria neste mês.']);
        }

        $budget->update($validated);

        return redirect()->route('budgets.index')
            ->with('success', 'Orçamento atualizado com sucesso!');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();

        return redirect()->route('budgets.index')
            ->with('success', 'Orçamento excluído com sucesso!');
    }
} 