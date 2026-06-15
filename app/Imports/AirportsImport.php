<?php

namespace App\Imports;

use App\Models\Airport;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;

class AirportsImport implements ToCollection, WithHeadingRow
{
    public int $total    = 0;
    public int $imported = 0;
    public int $updated  = 0;
    public int $skipped  = 0;
    public int $failed   = 0;

    public array $errors = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $this->total++;

            $rowNum = $index + 2;

            $iataCode    = trim($row['locid'] ?? $row['iata_code'] ?? '');
            $airportName = trim($row['airport_name'] ?? $row['airportname'] ?? '');
            $city        = trim($row['city'] ?? '');
            $stateCode   = trim($row['st'] ?? $row['state_code'] ?? '');

            if (empty($iataCode) || empty($airportName) || empty($city)) {
                $this->failed++;
                $this->errors[] = "Row {$rowNum}: IATA code, Airport Name, and City are required.";
                continue;
            }

            try {
                $existing = Airport::where('iata_code', strtoupper($iataCode))->first();

                if ($existing) {
                    $existing->update([
                        'state_code'   => $stateCode ?: $existing->state_code,
                        'city'         => $city,
                        'airport_name' => $airportName,
                    ]);
                    $this->updated++;
                } else {
                    Airport::create([
                        'state_code'   => $stateCode,
                        'iata_code'    => strtoupper($iataCode),
                        'city'         => $city,
                        'airport_name' => $airportName,
                        'status'       => 'active',
                    ]);
                    $this->imported++;
                }
            } catch (\Exception $e) {
                $this->failed++;
                $this->errors[] = "Row {$rowNum}: " . $e->getMessage();
            }
        }
    }
}
