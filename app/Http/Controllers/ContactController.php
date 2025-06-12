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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
            ],
            'message' => 'required|string|max:1000',
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(\+62|62|0)8[1-9][0-9]{6,9}$/'
            ],
            'subject' => 'nullable|string|max:255',
        ], [
            'email.regex' => 'Email harus menggunakan domain @gmail.com.',
            'phone.regex' => 'Nomor telepon harus valid dan sesuai format Indonesia (contoh: 0812xxxxxx).',
        ]);

        // Create a new contact entry
        Contact::create($request->all());

        Mail::to('fakhrirrahman7@gmail.com')->send(new ContactMail($request->all()));


        return redirect()->back()->with('success', 'Thank you for contacting us!');
    }
}
