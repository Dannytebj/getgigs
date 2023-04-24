<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // show register create form
    public function create()
    {
        return view('users.register');
    }

    // create new user
    public function store()
    {
        // validate the form
        $attributes = request()->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'min:7', 'max:255']
        ]);
        // hash the password
        $attributes['password'] = bcrypt($attributes['password']);

        $user = User::create($attributes);

        // sign the user in
        auth()->login($user);

        return redirect('/')->with('message', 'Your account has been created.');
    }

    public function logout()
    {
        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged out.');
    }

    public function login()
    {
        return view('users.login');
    }

    public function authenticate()
    {
        $attributes = request()->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);

        if (auth()->attempt($attributes)) {
            request()->session()->regenerate();

            return redirect('/')->with('message', 'Welcome back!');
        }
        return back()->withErrors([
            'email' => 'Your provided credentials could not be verified.'
        ])->onlyInput('email');
    }
}
