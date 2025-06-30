<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class SimpleEmailVerificationController extends Controller
{
    public function verify(Request $request, $id, $hash)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk memverifikasi email.');
        }

        // Find user
        $user = User::findOrFail($id);

        // Check if this is the correct user
        if ($user->id !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses untuk memverifikasi email ini.');
        }

        // Check if email is already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home')->with('info', 'Email Anda sudah terverifikasi sebelumnya.');
        }

        // Verify hash
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('home')->with('error', 'Link verifikasi tidak valid.');
        }

        // Check if link is expired (24 hours)
        $expires = $request->query('expires');
        if ($expires && Carbon::now()->timestamp > $expires) {
            return redirect()->route('verification.notice')->with('error', 'Link verifikasi sudah kadaluarsa. Silakan minta link baru.');
        }

        // Mark email as verified
        $user->markEmailAsVerified();

        return redirect()->route('home')->with('success', 'Email berhasil diverifikasi! Selamat datang di Mutiara Nasional Line.');
    }

    public function notice()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        return view('auth.verify-email');
    }

    public function send(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Link verifikasi telah dikirim ulang ke email Anda!');
    }
}
