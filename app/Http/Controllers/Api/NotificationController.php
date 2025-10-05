<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Students;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get a listing of students (with optional search).
     */
    public function index(Request $request)
    {
        $query = Students::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $students = $query->paginate(20);

        return response()->json($students, 200);
    }

    /**
     * Send an SMS to one or more selected students.
     */
    public function sendSms(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'message' => 'required|string|max:160',
        ]);

        $studentIds = $request->input('student_ids');
        $message = $request->input('message');

        $students = Students::whereIn('id', $studentIds)->get();

        $sentTo = [];

        foreach ($students as $student) {
            if ($student->phone) {
                // 👉 Replace with actual SMS service integration
                // SmsService::send($student->phone, $message);

                \Log::info("Sending SMS to " . $student->phone . ": " . $message);
                $sentTo[] = $student->phone;
            }
        }

        return response()->json([
            'message' => 'SMS sent successfully!',
            'sent_to' => $sentTo
        ], 200);
    }
}
