<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Students;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the students.
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
        $students->appends($request->only('search'));

        // For AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'table_rows' => view('admin.notifications.partials.table_rows', compact('students'))->render(),
                'pagination_links' => $students->links()->render()
            ]);
        }

        return view('admin.notifications.index', compact('students'));
    }


    /**
     * Send an SMS to one or more selected students.
     */
    public function sendSms(Request $request)
    {
        // 1. Validate the request data
        $request->validate([
            'student_ids' => 'required|array',
            'message' => 'required|string|max:160',
        ]);

        $studentIds = $request->input('student_ids');
        $message = $request->input('message');

        // 2. Fetch the selected students from the database
        $students = Students::whereIn('id', $studentIds)->get();

        // 3. Loop through students and send an SMS
        foreach ($students as $student) {
            // Check if the student has a phone number
            if ($student->phone) {
                // Here, you would call a function or a service to send the SMS
                // Example using a fictional sms service:
                // SmsService::send($student->phone, $message);

                // For now, we'll just log it for demonstration
                \Log::info("Sending SMS to " . $student->phone . ": " . $message);
            }
        }

        return response()->json(['success' => 'SMS sent successfully!']);
    }
}