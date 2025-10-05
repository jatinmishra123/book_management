<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Students; // Singular model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;

class StudentsController extends Controller
{
    // List all students (optional pagination)
    public function index(Request $request)
    {
        $perPage = $request->input('per_page');

        if ($perPage) {
            $students = Students::orderBy('created_at', 'DESC')->paginate($perPage);
        } else {
            $students = Students::orderBy('created_at', 'DESC')->get();
        }

        return response()->json($students);
    }

    // Store new student
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'required|in:Male,Female',
            'enrollment_date' => 'required|date',
            'expiry_date' => 'nullable|date',
            'total_amount' => 'nullable|numeric',
            'paid_amount' => 'nullable|numeric',
            'payment_status' => 'nullable|in:Paid,Pending',
            'hall' => 'nullable|string',
            'seat' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Handle profile image
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('students', 'public');
            $data['profile_image'] = $path;
        }

        $student = Students::create($data);

        return response()->json(['message' => 'Student added successfully!', 'student' => $student], 201);
    }

    // Show single student (null-safe)
    public function show($id)
    {
        $student = Students::find($id);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        return response()->json($student);
    }

    // Update student (null-safe)
    public function update(Request $request, $id)
    {
        $student = Students::find($id);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'required|in:Male,Female',
            'enrollment_date' => 'required|date',
            'expiry_date' => 'nullable|date',
            'total_amount' => 'nullable|numeric',
            'paid_amount' => 'nullable|numeric',
            'payment_status' => 'nullable|in:Paid,Pending',
            'hall' => 'nullable|string',
            'seat' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Handle profile image
        if ($request->hasFile('profile_image')) {
            if ($student->profile_image && Storage::disk('public')->exists($student->profile_image)) {
                Storage::disk('public')->delete($student->profile_image);
            }
            $path = $request->file('profile_image')->store('students', 'public');
            $data['profile_image'] = $path;
        }

        $student->update($data);

        return response()->json(['message' => 'Student updated successfully!', 'student' => $student], 200);
    }

    // Delete student (null-safe)
    public function destroy($id)
    {
        $student = Students::find($id);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        if ($student->profile_image && Storage::disk('public')->exists($student->profile_image)) {
            Storage::disk('public')->delete($student->profile_image);
        }

        $student->delete();

        return response()->json(['message' => 'Student deleted successfully!'], 200);
    }

    // Export CSV
    public function exportCsv()
    {
        return Excel::download(new StudentsExport, 'students.csv');
    }

    // Export Excel
    public function exportExcel()
    {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }
}
