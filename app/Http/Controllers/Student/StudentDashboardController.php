<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Flashcard;
use App\Models\FlashcardMetadata;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::guard('student')->user();

        // Stats
        $totalSets      = Document::where('processing_status', 'published')->count();
        $studiedThisWeek = 0; // Will be populated when study tracking is built
        $unitsCovered   = FlashcardMetadata::distinct('unit_code')->count('unit_code');

        // Filter inputs
        $unitCode  = $request->input('unit_code');
        $lecturer  = $request->input('lecturer');
        $examType  = $request->input('exam_type');
        $year      = $request->input('year');
        $keyword   = $request->input('keyword');

        // Base query — only published documents with their flashcards and metadata
        $query = Document::where('processing_status', 'published')
            ->with(['flashcards.metadata'])
            ->whereHas('flashcards.metadata', function ($q) use ($unitCode, $lecturer, $examType, $year, $keyword) {
                if ($unitCode) {
                    $q->where('unit_code', $unitCode);
                }
                if ($lecturer) {
                    $q->where('lecturer', $lecturer);
                }
                if ($examType) {
                    $q->where('exam_type', $examType);
                }
                if ($year) {
                    $q->where('academic_year', $year);
                }
                if ($keyword) {
                    $q->where(function ($q2) use ($keyword) {
                        $q2->where('unit_code', 'like', "%{$keyword}%")
                           ->orWhere('lecturer', 'like', "%{$keyword}%");
                    });
                }
            });

        $exactMatchFound = $query->count() > 0;
        $flashcardSets   = $query->latest('uploaded_at')->paginate(6);

        // If no exact match, get recommended alternatives
        $recommendations = collect();
        if (!$exactMatchFound && ($unitCode || $lecturer || $examType || $year || $keyword)) {
            $recommendations = Document::where('processing_status', 'published')
                ->with(['flashcards.metadata'])
                ->whereHas('flashcards.metadata', function ($q) use ($unitCode) {
                    if ($unitCode) {
                        $q->where('unit_code', 'like', "%{$unitCode}%");
                    }
                })
                ->latest('uploaded_at')
                ->limit(4)
                ->get();
        }

        // Log the search if filters were applied
        if ($request->hasAny(['unit_code', 'lecturer', 'exam_type', 'year', 'keyword'])) {
            \App\Models\SearchLog::create([
                'student_id'        => $student->id,
                'query_string'      => $keyword ?? '',
                'filter_params'     => $request->only(['unit_code', 'lecturer', 'exam_type', 'year']),
                'exact_match_found' => $exactMatchFound,
            ]);
        }

        // Dropdown options for filters
        $unitCodes  = FlashcardMetadata::distinct()->pluck('unit_code');
        $lecturers  = FlashcardMetadata::distinct()->pluck('lecturer');
        $examTypes  = FlashcardMetadata::distinct()->pluck('exam_type');
        $years      = FlashcardMetadata::distinct()->pluck('academic_year');

        return view('student.dashboard', compact(
            'student',
            'totalSets',
            'studiedThisWeek',
            'unitsCovered',
            'flashcardSets',
            'recommendations',
            'exactMatchFound',
            'unitCodes',
            'lecturers',
            'examTypes',
            'years',
        ));
    }
}