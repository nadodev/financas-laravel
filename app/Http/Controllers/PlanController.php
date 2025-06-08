<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        $currentPlan = auth()->user()->plan;

        return view('plans.index', compact('plans', 'currentPlan'));
    }

    public function show(Plan $plan)
    {
        return view('plans.show', compact('plan'));
    }
} 