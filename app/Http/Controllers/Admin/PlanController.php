<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount('users')->get();
        return view('admin.plans.index', compact('plans'));
    }
    
    public function create()
    {
        return view('admin.plans.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'features' => 'required|array|min:1',
            'features.*' => 'required|string|max:255'
        ]);
        
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = true;
        
        Plan::create($validated);
        
        return redirect()->route('admin.plans.index')
            ->with('success', 'Plano criado com sucesso!');
    }
    
    public function show(Plan $plan)
    {
        $plan->loadCount('users');
        return view('admin.plans.show', compact('plan'));
    }
    
    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }
    
    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'features' => 'required|array|min:1',
            'features.*' => 'required|string|max:255'
        ]);
        
        $validated['slug'] = Str::slug($validated['name']);
        
        $plan->update($validated);
        
        return redirect()->route('admin.plans.index')
            ->with('success', 'Plano atualizado com sucesso!');
    }
    
    public function destroy(Plan $plan)
    {
        if ($plan->users()->exists()) {
            return redirect()->route('admin.plans.index')
                ->with('error', 'Não é possível excluir um plano que possui usuários.');
        }
        
        $plan->delete();
        
        return redirect()->route('admin.plans.index')
            ->with('success', 'Plano excluído com sucesso!');
    }
    
    public function toggleStatus(Plan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);
        
        return redirect()->route('admin.plans.index')
            ->with('success', 'Status do plano atualizado com sucesso!');
    }
} 