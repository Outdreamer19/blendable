<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Marketing/Contact');
    }

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Send email notification
            Mail::raw(
                "New Contact Form Submission\n\n".
                "Name: {$request->name}\n".
                "Email: {$request->email}\n".
                "Subject: {$request->subject}\n\n".
                "Message:\n{$request->message}",
                function ($message) use ($request) {
                    $message->to(config('mail.from.address'))
                        ->subject("Contact Form: {$request->subject}")
                        ->replyTo($request->email, $request->name);
                }
            );

            return back()->with('success', 'Thank you for your message! We will get back to you soon.');
        } catch (\Exception $e) {
            \Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return back()->with('error', 'Sorry, there was an error sending your message. Please try again later or email us directly.');
        }
    }
}
