<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;

class StudentsController extends Controller
{
    public function index()
    {
        $students = Students::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
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

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('students', 'public');
            $data['profile_image'] = $path;
        }

        Students::create($data);

        return redirect()->route('admin.students.index')->with('success', 'Student added successfully!');
    }

    public function edit(Students $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Students $student)
    {
        $data = $request->validate([
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

        if ($request->hasFile('profile_image')) {
            if ($student->profile_image && Storage::disk('public')->exists($student->profile_image)) {
                Storage::disk('public')->delete($student->profile_image);
            }
            $path = $request->file('profile_image')->store('students', 'public');
            $data['profile_image'] = $path;
        }

        $student->update($data);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully!');
    }

    public function destroy(Students $student)
    {
        if ($student->profile_image && Storage::disk('public')->exists($student->profile_image)) {
            Storage::disk('public')->delete($student->profile_image);
        }

        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully!');
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
