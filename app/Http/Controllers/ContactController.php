<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Honeypot: real users never fill the hidden `website` field.
        if ($request->filled('website')) {
            return back()->with('success', 'Your message has been sent successfully. We will get back to you soon.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\-\']+$/u'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        ContactMessage::create([
            'user_id' => Auth::id(),
            'name' => strip_tags($request->name),
            'email' => $request->email,
            'subject' => strip_tags($request->subject),
            'message' => strip_tags($request->message),
        ]);

        return back()->with('success', 'Your message has been sent successfully. We will get back to you soon.');
    }
}
