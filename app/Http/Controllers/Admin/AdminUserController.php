<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\ClassRep;
use Illuminate\Support\Facades\Auth;

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
            $student->classRep->update([
                'approved'    => false,
                'approved_at' => null,
            ]);
        }

        return back()->with('success', 'Class rep status revoked.');
    }

    public function promoteToClassRep(Request $request, Student $student)
    {
        $request->validate([
            'class_name' => ['required', 'string', 'max:255'],
        ]);

        $admin = Auth::guard('admin')->user();

        // Reactivate existing record if one exists, otherwise create new
        $classRep = ClassRep::firstOrNew(['student_id' => $student->id]);

        $classRep->class_name  = $request->class_name;
        $classRep->approved    = true;
        $classRep->approved_at = now();
        $classRep->approved_by = $admin->id;
        $classRep->save();

        $student->update(['role' => 'class_rep']);

        return back()->with('success', "{$student->name} has been promoted to Class Rep.");
    }   
}