<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        
    }
    public function login()
    {
        return view('pages.login');
    }
    public function login_action(Request $request)
    {
        $request->validate([
            'password' => 'required|min:1|max:50',
            'emailOrPhone' => 'required|min:1|max:50',
        ]);
        $user = User::where('email', $request->emailOrPhone)->orWhere('phone', $request->emailOrPhone)->first();
        if(!$user)
        {
            return redirect()->back()->withErrors(['User not found! Try again!'])->withInput(['emailOrPhone' => $request->emailOrPhone]);
        }
        if(Hash::check($request->password, $user->password))
        {
            Auth::login(
                $user, true 
            );
            return redirect('after-login');
        }
        return redirect()->back()->withErrors(['Wrong password! Try again!'])->withInput(['emailOrPhone' => $request->emailOrPhone]);
    }
    public function register()
    {
        return view('pages.register');
    }
    function register_store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:50',
            'password' => 'required|min:2|max:50',
            'phone' => 'required|min:11|max:11|unique:users',
            'email' => 'max:50|unique:users',
        ]);
        Auth::login(
            User::create([  
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'email' => $request->email,
            ]), true 
        );
        return redirect('after-login');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
