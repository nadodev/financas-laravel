<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('plan')->paginate(10);
        return view('admin.users.index', compact('users'));
    }
    
    public function create()
    {
        $plans = Plan::where('is_active', true)->get();
        return view('admin.users.create', compact('plans'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'plan_id' => ['required', 'exists:plans,id'],
            'role' => ['required', 'in:user,admin']
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }
    
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }
    
    public function edit(User $user)
    {
        $plans = Plan::where('is_active', true)->get();
        return view('admin.users.edit', compact('user', 'plans'));
    }
    
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'plan_id' => ['required', 'exists:plans,id'],
            'role' => ['required', 'in:user,admin']
        ]);
        
        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }
    
    public function destroy(User $user)
    {
        if ($user->isAdmin() && User::where('role', 'admin')->count() === 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Não é possível excluir o último administrador do sistema.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }
    
    public function changePlan(Request $request, User $user)
    {
        $request->validate([
            'plan_id' => ['required', 'exists:plans,id']
        ]);
        
        $user->plan_id = $request->plan_id;
        $user->save();
        
        return back()->with('success', 'Plano do usuário alterado com sucesso.');
    }
}