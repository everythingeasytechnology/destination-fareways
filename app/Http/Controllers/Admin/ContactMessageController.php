<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the contact messages.
     */
    public function index()
    {
        $messages = ContactMessage::orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.contacts.index', compact('messages'));
    }

    /**
     * Display the specified contact message in a modal partial view & mark as Read.
     */
    public function show(ContactMessage $contact)
    {
        // Mark as Read if it was unread
        if (!$contact->is_read) {
            $contact->update([
                'is_read' => true,
                'status' => 'read',
            ]);
        }

        return view('admin.contacts.show_modal_content', compact('contact'));
    }

    /**
     * Post a reply to the customer message.
     */
    public function reply(Request $request, ContactMessage $contact)
    {
        $validatedData = $request->validate([
            'admin_reply' => ['required', 'string'],
        ]);

        $contact->update([
            'admin_reply' => $validatedData['admin_reply'],
            'status' => 'replied',
        ]);

        // Simulating email dispatch to customer...
        
        return redirect()->route('admin.contacts.index')->with('success', 'Reply recorded and email drafted to customer successfully.');
    }

    /**
     * Remove the specified contact message (soft delete).
     */
    public function destroy(ContactMessage $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Message moved to trash successfully.');
    }
}
