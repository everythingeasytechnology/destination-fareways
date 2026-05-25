<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * Handle Newsletter Subscription via AJAX
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid email address.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $email = strtolower(trim($request->input('email')));
            
            // Check if already subscribed
            $subscriber = NewsletterSubscriber::where('email', $email)->first();
            
            if ($subscriber) {
                if ($subscriber->status === 'unsubscribed') {
                    $subscriber->update(['status' => 'subscribed']);
                    return response()->json([
                        'success' => true,
                        'message' => 'Welcome back! You have re-subscribed successfully.'
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'You are already subscribed to our flight deals newsletter!'
                ]);
            }

            NewsletterSubscriber::create([
                'email' => $email,
                'status' => 'subscribed'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed! Get ready for exclusive unpublished flight deals.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try subscribing again later.'
            ], 500);
        }
    }
}
