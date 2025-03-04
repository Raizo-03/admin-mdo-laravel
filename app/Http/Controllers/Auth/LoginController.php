<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{  
    public function showLoginForm()
    {
        return view('auth.login'); // Show login page
    }

    public function login(Request $request)
    {
        // Validate input fields
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Attempt to authenticate using the 'admin' guard
        if (Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect()->route('dashboard'); // Redirect on success
        }

        // If authentication fails, show error message
        return back()->withErrors(['login' => 'Invalid username or password'])->withInput();
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('login'); // Redirect to login page after logout
    }
}
