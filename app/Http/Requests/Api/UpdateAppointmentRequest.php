<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'doctor_id' => 'sometimes|required|exists:doctors,id',
            'appointment_date' => 'sometimes|required|date_format:Y-m-d H:i:s|after:now',
            'notes' => 'nullable|string',
            'status' => 'sometimes|string|in:pending,confirmed,completed,cancelled',
        ];
    }
}
