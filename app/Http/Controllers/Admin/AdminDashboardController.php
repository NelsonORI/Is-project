<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ClassRep;
use App\Models\Document;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Stats
        $totalStudents      = Student::count();
        $totalClassReps     = ClassRep::where('approved', true)->count();
        $totalDocuments     = Document::count();
        $unmatchedSearches  = SearchLog::where('exact_match_found', false)->count();

        // Recent users
        $recentUsers = Student::latest('created_at')->take(5)->get();

        // Pending class rep approval requests
        $pendingApprovals = ClassRep::where('approved', false)
            ->with('student')
            ->latest('created_at')
            ->take(5)
            ->get();

        // Top unmatched search queries for gap report preview
        $gapReport = SearchLog::where('exact_match_found', false)
            ->select('query_string', DB::raw('count(*) as total'))
            ->whereNotNull('query_string')
            ->where('query_string', '!=', '')
            ->groupBy('query_string')
            ->orderByDesc('total')
            ->take(4)
            ->get();

        $maxGapCount = $gapReport->max('total') ?: 1;

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalClassReps',
            'totalDocuments',
            'unmatchedSearches',
            'recentUsers',
            'pendingApprovals',
            'gapReport',
            'maxGapCount',
        ));
    }

    // Approve a pending class rep request
    public function approveClassRep(Request $request, ClassRep $classRep)
    {
        $admin = Auth::guard('admin')->user();

        $classRep->update([
            'approved'    => true,
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);

        $classRep->student->update(['role' => 'class_rep']);

        return back()->with('success', 'Class rep request approved.');
    }

    // Reject / revoke a class rep request
    public function rejectClassRep(Request $request, ClassRep $classRep)
    {
        $classRep->student->update(['role' => 'student']);
        $classRep->delete();

        return back()->with('success', 'Class rep request rejected.');
    }
}