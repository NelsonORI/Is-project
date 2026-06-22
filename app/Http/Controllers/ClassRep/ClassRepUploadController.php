<?php

namespace App\Http\Controllers\ClassRep;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Flashcard;
use App\Models\FlashcardMetadata;
use App\Services\FlaskOcrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClassRepUploadController extends Controller
{
    protected FlaskOcrService $ocrService;

    public function __construct(FlaskOcrService $ocrService)
    {
        $this->ocrService = $ocrService;
    }

    // Step 1 — Show upload form
    public function step1()
    {
        return view('classrep.upload.step1');
    }

    // Step 1 — Handle PDF upload
    public function step1Store(Request $request)
    {
        $request->validate([
            'pdf' => [
                'required',
                'file',
                'mimes:pdf',
                'max:20480', // 20MB
            ],
        ], [
            'pdf.required' => 'Please select a PDF file to upload.',
            'pdf.mimes'    => 'Only PDF files are accepted.',
            'pdf.max'      => 'The file must not exceed 20MB.',
        ]);

        $file     = $request->file('pdf');
        $filename = $file->getClientOriginalName();
        $path     = $file->store('uploads/papers', 'local');

        // Store in session to carry across steps
        session([
            'upload.filename' => $filename,
            'upload.path'     => $path,
        ]);

        return redirect()->route('classrep.upload.step2');
    }

    // Step 2 — Show metadata form
    public function step2()
    {
        // Guard — must have completed step 1
        if (!session('upload.path')) {
            return redirect()->route('classrep.upload.step1');
        }

        return view('classrep.upload.step2');
    }

    // Step 2 — Handle metadata submission and trigger processing
    public function step2Store(Request $request)
    {
        if (!session('upload.path')) {
            return redirect()->route('classrep.upload.step1');
        }

        $request->validate([
            'unit_code'     => ['required', 'string', 'max:20'],
            'unit_name'     => ['required', 'string', 'max:255'],
            'lecturer'      => ['required', 'string', 'max:255'],
            'academic_year' => ['required', 'string', 'max:10'],
            'semester'      => ['required', 'in:Semester 1,Semester 2,Trimester 1,Trimester 2,Trimester 3'],
            'exam_type'     => ['required', 'in:Mid-term,Final,Quiz,CAT'],
        ]);

        $student  = Auth::guard('student')->user();
        $classRep = $student->classRep;

        // Create the document record
        $document = Document::create([
            'class_rep_id'      => $classRep->id,
            'original_filename' => session('upload.filename'),
            'storage_path'      => session('upload.path'),
            'processing_status' => 'processing',
        ]);

        // Store document id and metadata in session
        session([
            'upload.document_id' => $document->id,
            'upload.metadata'    => $request->only([
                'unit_code',
                'unit_name',
                'lecturer',
                'academic_year',
                'semester',
                'exam_type',
            ]),
        ]);

        return redirect()->route('classrep.upload.step3');
    }

    // Step 3 — Show processing screen and trigger OCR
    public function step3()
    {
        if (!session('upload.document_id')) {
            return redirect()->route('classrep.upload.step1');
        }

        return view('classrep.upload.step3');
    }

    // Step 3 — AJAX endpoint polled by the browser to run OCR
    public function process(Request $request)
    {
        $documentId = session('upload.document_id');
        $metadata   = session('upload.metadata');

        if (!$documentId || !$metadata) {
            return response()->json(['error' => 'Session expired. Please start again.'], 422);
        }

        $document = Document::findOrFail($documentId);

        try {
            // Call Flask microservice
            $result = $this->ocrService->processDocument(
                $document->storage_path,
                $metadata
            );

            if (!$result['success']) {
                $document->update(['processing_status' => 'processing']);
                return response()->json(['error' => $result['error']], 500);
            }

            // Store flashcards and metadata
            DB::transaction(function () use ($document, $result, $metadata) {
                $document->update(['processing_status' => 'ocr_done']);

                foreach ($result['flashcards'] as $index => $card) {
                    $flashcard = Flashcard::create([
                        'document_id'      => $document->id,
                        'question'         => $card['question'],
                        'answer'           => $card['answer'],
                        'confidence_score' => $card['confidence_score'] ?? null,
                        'card_order'       => $index + 1,
                    ]);

                    FlashcardMetadata::create([
                        'flashcard_id'  => $flashcard->id,
                        'unit_code'     => $metadata['unit_code'],
                        'lecturer'      => $metadata['lecturer'],
                        'exam_type'     => $metadata['exam_type'],
                        'semester'      => $metadata['semester'],
                        'academic_year' => $metadata['academic_year'],
                    ]);
                }

                $document->update(['processing_status' => 'published']);
            });

            // Store summary for step 4
            session([
                'upload.summary' => [
                    'unit_code'      => $metadata['unit_code'],
                    'unit_name'      => $metadata['unit_name'],
                    'exam_type'      => $metadata['exam_type'],
                    'card_count'     => count($result['flashcards']),
                    'uploaded_at'    => now()->format('d M Y, H:i'),
                    'document_id'    => $document->id,
                ],
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Upload processing failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Processing failed. Please try again.'], 500);
        }
    }

    // Step 4 — Show success screen
    public function step4()
    {
        if (!session('upload.summary')) {
            return redirect()->route('classrep.upload.step1');
        }

        $summary = session('upload.summary');

        // Clear upload session data
        session()->forget([
            'upload.filename',
            'upload.path',
            'upload.document_id',
            'upload.metadata',
            'upload.summary',
        ]);

        return view('classrep.upload.step4', compact('summary'));
    }
}