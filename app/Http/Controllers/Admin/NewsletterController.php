<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the newsletter subscribers.
     */
    public function index()
    {
        $subscribers = NewsletterSubscriber::orderBy('created_at', 'desc')->get();
        return view('admin.newsletter.index', compact('subscribers'));
    }

    /**
     * Toggle subscriber status to unsubscribed.
     */
    public function unsubscribe(NewsletterSubscriber $subscriber)
    {
        $subscriber->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
        ]);

        return redirect()->route('admin.newsletter.index')->with('success', 'Subscriber status set to unsubscribed.');
    }

    /**
     * Remove the specified subscriber permanently.
     */
    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()->route('admin.newsletter.index')->with('success', 'Subscriber deleted permanently.');
    }

    /**
     * Export all newsletter subscribers to CSV.
     */
    public function exportCsv()
    {
        $subscribers = NewsletterSubscriber::orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subscribers_' . date('Ymd_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($subscribers) {
            $file = fopen('php://output', 'w');
            
            // CSV Columns Header
            fputcsv($file, ['ID', 'Email', 'Name', 'Status', 'IP Address', 'Subscribed At', 'Unsubscribed At']);

            foreach ($subscribers as $sub) {
                fputcsv($file, [
                    $sub->id,
                    $sub->email,
                    $sub->name ?? 'N/A',
                    ucfirst($sub->status),
                    $sub->ip,
                    $sub->created_at->toDateTimeString(),
                    $sub->unsubscribed_at ? $sub->unsubscribed_at->toDateTimeString() : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
