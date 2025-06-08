<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::where('user_id', auth()->id())
            ->with(['category'])
            ->orderBy('start_date')
            ->get();

        return view('budgets.index', compact('budgets'));
    }

    public function create()
    {
        $categories = Category::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('budgets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'recurrence' => 'nullable|in:monthly,quarterly,yearly',
            'description' => 'nullable|string|max:255'
        ]);

        $validated['user_id'] = auth()->id();

        // Verificar se já existe um orçamento para a categoria no mesmo período
        $existingBudget = Budget::where('user_id', auth()->id())
            ->where('category_id', $validated['category_id'])
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->whereNull('start_date')
                        ->whereNull('end_date');
                })->orWhere(function($q) use ($validated) {
                    if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
                        $q->where(function($inner) use ($validated) {
                            $inner->where('start_date', '<=', $validated['end_date'])
                                ->where('end_date', '>=', $validated['start_date']);
                        });
                    }
                });
            })
            ->exists();

        if ($existingBudget) {
            return back()
                ->withInput()
                ->withErrors(['category_id' => 'Já existe um orçamento para esta categoria no período selecionado.']);
        }

        Budget::create($validated);

        return redirect()
            ->route('budgets.index')
            ->with('success', 'Orçamento criado com sucesso!');
    }

    public function edit(Budget $budget)
    {
        $this->authorize('update', $budget);

        $categories = Category::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('budgets.edit', compact('budget', 'categories'));
    }

    public function update(Request $request, Budget $budget)
    {
        $this->authorize('update', $budget);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'recurrence' => 'nullable|in:monthly,quarterly,yearly',
            'description' => 'nullable|string|max:255'
        ]);

        // Verificar se já existe outro orçamento para a categoria no mesmo período
        $existingBudget = Budget::where('user_id', auth()->id())
            ->where('category_id', $validated['category_id'])
            ->where('id', '!=', $budget->id)
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->whereNull('start_date')
                        ->whereNull('end_date');
                })->orWhere(function($q) use ($validated) {
                    if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
                        $q->where(function($inner) use ($validated) {
                            $inner->where('start_date', '<=', $validated['end_date'])
                                ->where('end_date', '>=', $validated['start_date']);
                        });
                    }
                });
            })
            ->exists();

        if ($existingBudget) {
            return back()
                ->withInput()
                ->withErrors(['category_id' => 'Já existe um orçamento para esta categoria no período selecionado.']);
        }

        $budget->update($validated);

        return redirect()
            ->route('budgets.index')
            ->with('success', 'Orçamento atualizado com sucesso!');
    }

    public function destroy(Budget $budget)
    {
        $this->authorize('delete', $budget);

        $budget->delete();

        return redirect()
            ->route('budgets.index')
            ->with('success', 'Orçamento excluído com sucesso!');
    }
} 