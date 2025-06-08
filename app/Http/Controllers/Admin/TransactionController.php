<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'category'])
            ->latest();
            
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }
        
        $transactions = $query->paginate(15);
        
        return view('admin.transactions.index', compact('transactions'));
    }
    
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'category']);
        return view('admin.transactions.show', compact('transaction'));
    }
} 