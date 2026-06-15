<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use App\Imports\AirportsImport;
use App\Exports\AirportSampleExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AirportController extends Controller
{
    public function index(Request $request)
    {
        $query = Airport::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('iata_code', 'like', "%{$s}%")
                  ->orWhere('city', 'like', "%{$s}%")
                  ->orWhere('airport_name', 'like', "%{$s}%")
                  ->orWhere('state_code', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $airports = $query->orderBy('airport_name')->paginate(25)->withQueryString();

        return view('admin.airports.index', compact('airports'));
    }

    public function create()
    {
        return view('admin.airports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'state_code'   => ['nullable', 'string', 'max:10'],
            'iata_code'    => ['required', 'string', 'max:10', 'unique:airports,iata_code'],
            'city'         => ['required', 'string', 'max:100'],
            'airport_name' => ['required', 'string', 'max:255'],
            'status'       => ['required', 'in:active,inactive'],
        ]);

        $validated['iata_code'] = strtoupper($validated['iata_code']);

        Airport::create($validated);

        return redirect()->route('admin.airports.index')->with('success', 'Airport created successfully.');
    }

    public function edit(Airport $airport)
    {
        return view('admin.airports.edit', compact('airport'));
    }

    public function update(Request $request, Airport $airport)
    {
        $validated = $request->validate([
            'state_code'   => ['nullable', 'string', 'max:10'],
            'iata_code'    => ['required', 'string', 'max:10', 'unique:airports,iata_code,' . $airport->id],
            'city'         => ['required', 'string', 'max:100'],
            'airport_name' => ['required', 'string', 'max:255'],
            'status'       => ['required', 'in:active,inactive'],
        ]);

        $validated['iata_code'] = strtoupper($validated['iata_code']);

        $airport->update($validated);

        return redirect()->route('admin.airports.index')->with('success', 'Airport updated successfully.');
    }

    public function destroy(Airport $airport)
    {
        $airport->delete();

        return redirect()->route('admin.airports.index')->with('success', 'Airport deleted successfully.');
    }

    public function importForm()
    {
        return view('admin.airports.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ]);

        $import = new AirportsImport();

        Excel::import($import, $request->file('file'));

        $result = [
            'total'    => $import->total,
            'imported' => $import->imported,
            'updated'  => $import->updated,
            'skipped'  => 0,
            'failed'   => $import->failed,
            'errors'   => $import->errors,
        ];

        return view('admin.airports.import', compact('result'));
    }

    public function downloadSample()
    {
        return Excel::download(new AirportSampleExport(), 'airports_sample.xlsx');
    }
}
