<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $key = 'contact:'.sha1($request->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->with('error', 'Too many messages from this device — please wait a few minutes and try again.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        ContactMessage::create($data);

        RateLimiter::hit($key, 300);

        return back()->with('status', 'Thanks for reaching out! The Fiesta team will get back to you soon.');
    }
}
