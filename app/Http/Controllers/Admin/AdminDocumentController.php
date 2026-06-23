<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['classRep.student', 'flashcards.metadata']);

        if ($request->filled('status')) {
            $query->where('processing_status', $request->status);
        }

        $documents = $query->latest('uploaded_at')->paginate(15)->withQueryString();

        return view('admin.documents', compact('documents'));
    }

    // Delete a document and its flashcards
    public function destroy(Document $document)
    {
        // Delete the stored PDF file
        if ($document->storage_path && Storage::exists($document->storage_path)) {
            Storage::delete($document->storage_path);
        }

        // Flashcards and metadata cascade delete via foreign keys
        $document->delete();

        return back()->with('success', 'Document and its flashcards have been deleted.');
    }
}