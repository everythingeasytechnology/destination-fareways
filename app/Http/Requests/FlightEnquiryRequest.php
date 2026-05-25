<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FlightEnquiryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'from_airport' => ['required', 'string', 'max:100'],
            'to_airport' => ['required', 'string', 'max:100'],
            'departure_date' => ['required', 'date'],
            'return_date' => ['nullable', 'date', 'after_or_equal:departure_date'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['nullable', 'integer', 'min:0'],
            'infants' => ['nullable', 'integer', 'min:0'],
            'cabin_class' => ['required', 'string', 'in:economy,premium_economy,business,first'],
            'trip_type' => ['required', 'string', 'in:one_way,round_trip,multi_city'],
            'preferred_airline' => ['nullable', 'string', 'max:100'],
            'budget' => ['nullable', 'string', 'max:50'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
