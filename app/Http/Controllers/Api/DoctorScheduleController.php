<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorScheduleRequest;
use App\Models\DoctorSchedule;
use Illuminate\Http\JsonResponse;

class DoctorScheduleController extends Controller
{
    public function index(): JsonResponse
    {
        $schedules = DoctorSchedule::with('doctor')->get()->map(function (DoctorSchedule $schedule) {
            return $this->formatSchedule($schedule);
        });

        return response()->json($schedules);
    }

    public function store(DoctorScheduleRequest $request): JsonResponse
    {
        $schedule = DoctorSchedule::create($request->validated());

        return response()->json($this->formatSchedule($schedule->load('doctor')), 201);
    }

    public function show(DoctorSchedule $schedule): JsonResponse
    {
        return response()->json($this->formatSchedule($schedule->load('doctor')));
    }

    public function update(DoctorScheduleRequest $request, DoctorSchedule $schedule): JsonResponse
    {
        $schedule->update($request->validated());

        return response()->json($this->formatSchedule($schedule->refresh()->load('doctor')));
    }

    public function destroy(DoctorSchedule $schedule): JsonResponse
    {
        $schedule->delete();

        return response()->json(null, 204);
    }

    private function formatSchedule(DoctorSchedule $schedule): array
    {
        return [
            'id' => $schedule->id,
            'doctor_id' => $schedule->doctor_id,
            'doctor_name' => $schedule->doctor->name,
            'day' => $schedule->day,
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
            'created_at' => $schedule->created_at?->toDateTimeString(),
            'updated_at' => $schedule->updated_at?->toDateTimeString(),
        ];
    }
}
