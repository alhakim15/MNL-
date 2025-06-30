<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = auth()->user();

            // Check if email is verified
            if (is_null($user->email_verified_at)) {
                return redirect()->route('verification.notice')->with('warning', 'Silakan verifikasi email Anda terlebih dahulu untuk melanjutkan.');
            }

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->intended('/admin'); // masuk ke Filament
            } else {
                return redirect()->intended('/'); // user biasa
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null, // Will be set when user verifies email
        ]);

        // Role default
        $user->assignRole('user');

        // Send email verification
        $user->sendEmailVerificationNotification();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verification.notice')->with('success', 'Registrasi berhasil! Silakan periksa email Anda untuk verifikasi.');
    }
    public function showLogin()
    {
        return view('auth.login', ['title' => 'Login']);
    }

    public function showRegister()
    {
        return view('auth.register', ['title' => 'Register']);
    }
}
