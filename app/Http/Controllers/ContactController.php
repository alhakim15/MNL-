<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;


class ContactController extends Controller
{
    public function index()
    {
        return view('contactus');
    }
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
        ]);

        // Create a new contact entry
        Contact::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'message' => $request->input('message'),
            'phone' => $request->input('phone'),
            'subject' => $request->input('subject'),
        ]);

        Mail::to('fakhrirrahman7@gmail.com')->send(new ContactMail($request->all()));


        return redirect()->back()->with('success', 'Thank you for contacting us!');
    }
}
