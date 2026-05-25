<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Handle Contact Form Submissions
     */
    public function submit(ContactRequest $request)
    {
        try {
            ContactMessage::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'subject' => $request->input('subject', 'General Enquiry'),
                'message' => $request->input('message'),
                'ip' => $request->ip(),
                'is_read' => false,
                'status' => 'new'
            ]);

            return back()->with('success', 'Thank you! Your message has been sent successfully. Our support team will get in touch with you shortly.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'An error occurred while saving your message. Please try calling us directly.');
        }
    }
}
