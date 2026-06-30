<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAppointmentRequest;
use App\Http\Requests\Api\UpdateAppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $appointments = Appointment::with('doctor')
            ->where('user_id', $request->user()->id)
            ->get()
            ->map(function (Appointment $appointment) {
                return $this->formatAppointment($appointment);
            });

        return response()->json($appointments);
    }

    public function show(Request $request, Appointment $appointment): JsonResponse
    {
        if ($appointment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($this->formatAppointment($appointment->load('doctor')));
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $appointment = Appointment::create([
            'user_id' => $request->user()->id,
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'notes' => $request->notes ?? null,
            'status' => 'pending',
        ]);

        return response()->json($this->formatAppointment($appointment->load('doctor')), 201);
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment): JsonResponse
    {
        if ($appointment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $appointment->update($request->validated());

        return response()->json($this->formatAppointment($appointment->refresh()->load('doctor')));
    }

    public function destroy(Request $request, Appointment $appointment): JsonResponse
    {
        if ($appointment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $appointment->delete();

        return response()->json(null, 204);
    }

    private function formatAppointment(Appointment $appointment): array
    {
        return [
            'id' => $appointment->id,
            'doctor' => [
                'id' => $appointment->doctor->id,
                'name' => $appointment->doctor->name,
                'specialty' => $appointment->doctor->specialty,
            ],
            'appointment_date' => $appointment->appointment_date,
            'status' => $appointment->status,
            'notes' => $appointment->notes,
            'created_at' => $appointment->created_at?->toDateTimeString(),
            'updated_at' => $appointment->updated_at?->toDateTimeString(),
        ];
    }
}
