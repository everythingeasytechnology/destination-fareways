<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlightEnquiry;
use Illuminate\Http\Request;

class FlightEnquiryController extends Controller
{
    /**
     * Display a listing of the flight enquiries.
     */
    public function index(Request $request)
    {
        $query = FlightEnquiry::orderBy('created_at', 'desc');

        // Apply Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $enquiries = $query->get();
        return view('admin.enquiries.index', compact('enquiries'));
    }

    /**
     * Display the specified flight enquiry in a modal partial view.
     */
    public function show(FlightEnquiry $enquiry)
    {
        return view('admin.enquiries.show_modal_content', compact('enquiry'));
    }

    /**
     * Update the flight enquiry status or admin notes.
     */
    public function update(Request $request, FlightEnquiry $enquiry)
    {
        $validatedData = $request->validate([
            'status' => ['required', 'string', 'in:new,reviewed,quoted,booked,cancelled'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $enquiry->update($validatedData);

        return redirect()->route('admin.enquiries.index')->with('success', 'Flight enquiry updated successfully.');
    }

    /**
     * Remove the specified flight enquiry (soft delete).
     */
    public function destroy(FlightEnquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('success', 'Flight enquiry moved to trash successfully.');
    }

    /**
     * Export all flight enquiries as CSV.
     */
    public function exportCsv(Request $request)
    {
        $query = FlightEnquiry::orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $enquiries = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="flight_enquiries_' . date('Ymd_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($enquiries) {
            $file = fopen('php://output', 'w');
            
            // CSV Columns Header
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Phone', 'From Airport', 'To Airport', 
                'Departure Date', 'Return Date', 'Adults', 'Children', 'Infants', 
                'Cabin Class', 'Trip Type', 'Preferred Airline', 'Budget', 
                'Special Requests', 'IP Address', 'Status', 'Admin Notes', 'Created At'
            ]);

            foreach ($enquiries as $enquiry) {
                fputcsv($file, [
                    $enquiry->id,
                    $enquiry->name,
                    $enquiry->email,
                    $enquiry->phone,
                    $enquiry->from_airport,
                    $enquiry->to_airport,
                    $enquiry->departure_date ? $enquiry->departure_date->format('Y-m-d') : '',
                    $enquiry->return_date ? $enquiry->return_date->format('Y-m-d') : '',
                    $enquiry->adults,
                    $enquiry->children,
                    $enquiry->infants,
                    ucwords(str_replace('_', ' ', $enquiry->cabin_class)),
                    ucwords(str_replace('_', ' ', $enquiry->trip_type)),
                    $enquiry->preferred_airline ?? 'Any',
                    $enquiry->budget ?? 'Flexible',
                    $enquiry->special_requests,
                    $enquiry->ip,
                    ucfirst($enquiry->status),
                    $enquiry->admin_notes,
                    $enquiry->created_at->toDateTimeString()
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
