<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\FinancialGoal;
use App\Services\FinancialGoalService;
use Illuminate\Http\Request;
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
        $goals = $this->service->getAllByUser(auth()->id());
        return view('financial-goals.index', compact('goals'));
    }

    public function create()
    {
        $accounts = auth()->user()->accounts()->get();
        $statuses = FinancialGoal::$statuses;
        
        return view('financial-goals.create', compact('accounts', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0',
            'target_date' => 'required|date|after:today',
            'account_id' => 'nullable|exists:accounts,id',
            'current_amount' => 'nullable|numeric|min:0',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['current_amount'] = 0;
        $validated['status'] = 'in_progress';
        
        $this->service->create($validated);

        return redirect()->route('financial-goals.index')
            ->with('success', 'Objetivo financeiro criado com sucesso!');
    }

    public function show(FinancialGoal $financialGoal)
    {
        $this->authorize('view', $financialGoal);
        
        // Carrega o relacionamento de progresso
        $financialGoal->load('progress');
        
        return view('financial-goals.show', compact('financialGoal'));
    }

    public function edit(FinancialGoal $financialGoal)
    {
        $accounts = auth()->user()->accounts()->get();
        $statuses = FinancialGoal::$statuses;
        
        return view('financial-goals.edit', compact('financialGoal', 'accounts', 'statuses'));
    }

    public function update(Request $request, FinancialGoal $financialGoal)
    {
        $this->authorize('update', $financialGoal);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0',
            'target_date' => 'required|date|after:today',
            'account_id' => 'nullable|exists:accounts,id',
            'current_amount' => 'required|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_keys(FinancialGoal::$statuses)),
        ]);

        $this->service->update($financialGoal, $validated);

        return redirect()->route('financial-goals.index')
            ->with('success', 'Objetivo financeiro atualizado com sucesso!');
    }

    public function destroy(FinancialGoal $financialGoal)
    {
        $this->authorize('delete', $financialGoal);
        
        $this->service->delete($financialGoal);

        return redirect()->route('financial-goals.index')
            ->with('success', 'Objetivo financeiro excluído com sucesso!');
    }

    public function simulate(Request $request, FinancialGoal $financialGoal)
    {
        $this->authorize('view', $financialGoal);

        try {
            if ($request->has('monthly_amount')) {
                $result = $this->service->simulateByMonthlyAmount(
                    $financialGoal,
                    (float) $request->monthly_amount
                );
                return response()->json([
                    'type' => 'monthly_amount',
                    'months' => $result['months'],
                    'estimated_date' => $result['estimated_date'],
                    'monthly_amount' => $request->monthly_amount
                ]);
            } else {
                $result = $this->service->simulateByMonths(
                    $financialGoal,
                    (int) $request->months
                );
                return response()->json([
                    'type' => 'months',
                    'months' => $request->months,
                    'estimated_date' => $result['estimated_date'],
                    'monthly_amount' => $result['monthly_amount']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao simular objetivo financeiro', [
                'error' => $e->getMessage(),
                'goal_id' => $financialGoal->id,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'error' => 'Erro ao realizar simulação'
            ], 500);
        }
    }

    public function updateProgress(Request $request, FinancialGoal $financialGoal)
    {
        $this->authorize('update', $financialGoal);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        try {
            $this->service->updateProgress(
                $financialGoal,
                $validated['amount'],
                $validated['date'],
                $validated['notes'] ?? null
            );

            return redirect()->route('financial-goals.show', $financialGoal)
                ->with('success', 'Progresso registrado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar progresso do objetivo financeiro', [
                'error' => $e->getMessage(),
                'goal_id' => $financialGoal->id,
                'request_data' => $validated
            ]);

            return redirect()->route('financial-goals.show', $financialGoal)
                ->with('error', 'Erro ao registrar progresso. Por favor, tente novamente.');
        }
    }
} 