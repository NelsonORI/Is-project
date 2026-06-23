<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class StudyController extends Controller
{
    public function show(Document $document)
    {
        // Only allow access to published documents
        if ($document->processing_status !== 'published') {
            abort(404);
        }

        $flashcards = $document->flashcards()
            ->with('metadata')
            ->orderBy('card_order')
            ->get();

        if ($flashcards->isEmpty()) {
            abort(404, 'No flashcards found for this document.');
        }

        $metadata = $flashcards->first()->metadata;

        return view('student.study', compact('document', 'flashcards', 'metadata'));
    }
}