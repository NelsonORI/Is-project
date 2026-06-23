<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('admin.users', compact('users'));
    }

    // Suspend a student account
    public function suspend(Student $student)
    {
        $student->update(['status' => 'suspended']);
        return back()->with('success', 'Student account suspended.');
    }

    // Reactivate a suspended student account
    public function activate(Student $student)
    {
        $student->update(['status' => 'active']);
        return back()->with('success', 'Student account reactivated.');
    }

    // Revoke class rep status
    public function revokeClassRep(Student $student)
    {
        $student->update(['role' => 'student']);

        if ($student->classRep) {
            $student->classRep->delete();
        }

        return back()->with('success', 'Class rep status revoked.');
    }
}