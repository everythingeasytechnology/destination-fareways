<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display a listing of the leads.
     */
    public function index(Request $request)
    {
        $query = Lead::orderBy('created_at', 'desc');

        // Apply Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query->get();
        return view('admin.leads.index', compact('leads'));
    }

    /**
     * Display the specified lead in a modal partial view.
     */
    public function show(Lead $lead)
    {
        return view('admin.leads.show_modal_content', compact('lead'));
    }

    /**
     * Update the lead status or admin notes.
     */
    public function update(Request $request, Lead $lead)
    {
        $validatedData = $request->validate([
            'status' => ['required', 'string', 'in:new,contacted,converted,closed'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $lead->update($validatedData);

        return redirect()->route('admin.leads.index')->with('success', 'Lead updated successfully.');
    }

    /**
     * Remove the specified lead (soft delete).
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Lead moved to trash successfully.');
    }

    /**
     * Export all leads as CSV.
     */
    public function exportCsv(Request $request)
    {
        $query = Lead::orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="leads_' . date('Ymd_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($leads) {
            $file = fopen('php://output', 'w');
            
            // CSV Columns Header
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Phone', 'From City', 'To City', 
                'Travel Date', 'Return Date', 'Passengers', 'Cabin Class', 
                'Trip Type', 'Source Page', 'IP Address', 'Status', 'Admin Notes', 'Created At'
            ]);

            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->id,
                    $lead->name,
                    $lead->email,
                    $lead->phone,
                    $lead->from_city,
                    $lead->to_city,
                    $lead->travel_date ? $lead->travel_date->format('Y-m-d') : '',
                    $lead->return_date ? $lead->return_date->format('Y-m-d') : '',
                    $lead->passengers,
                    ucwords(str_replace('_', ' ', $lead->cabin_class)),
                    ucwords(str_replace('_', ' ', $lead->trip_type)),
                    $lead->source_page,
                    $lead->ip,
                    ucfirst($lead->status),
                    $lead->admin_notes,
                    $lead->created_at->toDateTimeString()
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
