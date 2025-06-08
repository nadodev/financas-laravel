<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\FinancialGoal;
use App\Models\FinancialGoalProgress;
use App\Services\FinancialGoalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FinancialGoalController extends Controller
{
    protected $service;

    public function __construct(FinancialGoalService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
        $this->authorizeResource(FinancialGoal::class, 'financialGoal');
    }

    public function index()
    {
        $financialGoals = Auth::user()->financialGoals()
            ->with('account')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('financial-goals.index', compact('financialGoals'));
    }

    public function create()
    {
        $accounts = Auth::user()->accounts;
        return view('financial-goals.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0',
            'target_date' => 'required|date|after:today',
            'account_id' => 'nullable|exists:accounts,id',
            'monthly_amount' => 'required|numeric|min:0',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['current_amount'] = 0;
        $validated['status'] = 'in_progress';
        $validated['start_date'] = now();

        $financialGoal = FinancialGoal::create($validated);

        return redirect()
            ->route('financial-goals.show', $financialGoal)
            ->with('success', 'Objetivo financeiro criado com sucesso!');
    }

    public function show(FinancialGoal $financialGoal)
    {
        $financialGoal->load('progress');
        return view('financial-goals.show', compact('financialGoal'));
    }

    public function edit(FinancialGoal $financialGoal)
    {
        $accounts = Auth::user()->accounts;
        $statuses = FinancialGoal::$statuses;
        return view('financial-goals.edit', compact('financialGoal', 'accounts', 'statuses'));
    }

    public function update(Request $request, FinancialGoal $financialGoal)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0',
            'target_date' => 'required|date|after:today',
            'account_id' => 'nullable|exists:accounts,id',
            'status' => 'required|in:in_progress,completed,cancelled',
        ]);

        $financialGoal->update($validated);

        return redirect()
            ->route('financial-goals.show', $financialGoal)
            ->with('success', 'Objetivo financeiro atualizado com sucesso!');
    }

    public function destroy(FinancialGoal $financialGoal)
    {
        $financialGoal->delete();

        return redirect()
            ->route('financial-goals.index')
            ->with('success', 'Objetivo financeiro excluído com sucesso!');
    }

    public function updateProgress(Request $request, FinancialGoal $financialGoal)
    {
        $this->authorize('update', $financialGoal);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $progress = new FinancialGoalProgress($validated);
        $financialGoal->progress()->save($progress);

        $financialGoal->current_amount += $validated['amount'];
        
        if ($financialGoal->current_amount >= $financialGoal->target_amount) {
            $financialGoal->status = 'completed';
        }
        
        $financialGoal->save();

        return redirect()
            ->route('financial-goals.show', $financialGoal)
            ->with('success', 'Progresso registrado com sucesso!');
    }
} 