<?php

namespace App\Http\Controllers\ClassRep;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\FlashcardMetadata;
use Illuminate\Support\Facades\Auth;

class ClassRepDashboardController extends Controller
{
    public function index()
    {
        $student  = Auth::guard('student')->user();
        $classRep = $student->classRep;

        // Total published documents across the platform
        $totalSets = Document::where('processing_status', 'published')->count();

        // Papers this class rep has uploaded
        $papersUploaded = Document::where('class_rep_id', $classRep->id)
            ->where('processing_status', 'published')
            ->count();

        // Distinct units covered across the platform
        $unitsCovered = FlashcardMetadata::distinct('unit_code')->count('unit_code');

        // This class rep's recent uploads
        $recentUploads = Document::where('class_rep_id', $classRep->id)
            ->where('processing_status', 'published')
            ->with(['flashcards.metadata'])
            ->latest('uploaded_at')
            ->take(5)
            ->get();

        return view('classrep.dashboard', compact(
            'student',
            'totalSets',
            'papersUploaded',
            'unitsCovered',
            'recentUploads',
        ));
    }
}