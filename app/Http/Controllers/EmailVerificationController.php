<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('send');
    }

    public function notice()
    {
        return view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('home')->with('success', 'Email berhasil diverifikasi! Selamat datang di Mutiara Nasional Line.');
    }

    public function send(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Link verifikasi telah dikirim ulang ke email Anda!');
    }
}
