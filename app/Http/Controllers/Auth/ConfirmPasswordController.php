<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ConfirmPasswordController extends Controller
{
    public function show()
    {
        return view('auth.confirm-password');
    }

    public function confirm(Request $request)
    {
        if (!Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors([
                'password' => 'A senha fornecida está incorreta.'
            ]);
        }

        session()->put('password_confirmed_at', time());

        return redirect()->intended(
            $request->session()->pull('url.intended', route('dashboard'))
        );
    }
} 