@extends('layouts.admin')
@section('title', 'Manage Documents')

@section('content')

@if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

{{-- Filters --}}
<div class="bg-white border border-gray-200 rounded-xl px-6 py-4 mb-6">
    <form method="GET" action="{{ route('admin.documents') }}" class="flex gap-3">
        <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="">All statuses</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="ocr_done" {{ request('status') === 'ocr_done' ? 'selected' : '' }}>OCR Done</option>
        </select>
        <button type="submit"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2 rounded-lg transition">
            Filter
        </button>
        <a href="{{ route('admin.documents') }}"
            class="border border-gray-300 text-gray-600 text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-50 transition">
            Clear
        </a>
    </form>
</div>

{{-- Documents Table --}}
<div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-xs text-gray-400 border-b border-gray-100">
                <th class="text-left pb-2 font-medium">Filename</th>
                <th class="text-left pb-2 font-medium">Unit</th>
                <th class="text-left pb-2 font-medium">Uploaded By</th>
                <th class="text-left pb-2 font-medium">Cards</th>
                <th class="text-left pb-2 font-medium">Status</th>
                <th class="text-left pb-2 font-medium">Date</th>
                <th class="text-right pb-2 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $document)
                @php
                    $meta = $document->flashcards->first()?->metadata;
                @endphp
                <tr class="border-b border-gray-50">
                    <td class="py-3 text-gray-700 max-w-xs truncate">{{ $document->original_filename }}</td>
                    <td class="py-3">
                        @if($meta)
                            <span class="bg-indigo-100 text-indigo-700 text-xs font-medium px-2 py-0.5 rounded-full">
                                {{ $meta->unit_code }}
                            </span>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="py-3 text-gray-500">{{ $document->classRep->student->name ?? '—' }}</td>
                    <td class="py-3 text-gray-500">{{ $document->flashcards->count() }} cards</td>
                    <td class="py-3">
                        @if($document->processing_status === 'published')
                            <span class="bg-green-100 text-green-700 text-xs font-medium px-2 py-0.5 rounded-full">Published</span>
                        @elseif($document->processing_status === 'ocr_done')
                            <span class="bg-amber-100 text-amber-700 text-xs font-medium px-2 py-0.5 rounded-full">OCR Done</span>
                        @else
                            <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-0.5 rounded-full">Processing</span>
                        @endif
                    </td>
                    <td class="py-3 text-gray-400 text-xs">
                        {{ \Carbon\Carbon::parse($document->uploaded_at)->format('d M Y') }}
                    </td>
                    <td class="py-3 text-right">
                        <form method="POST" action="{{ route('admin.documents.destroy', $document->id) }}"
                            onsubmit="return confirm('Delete this document and all its flashcards? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 border border-red-200 hover:bg-red-50 rounded px-2 py-1">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $documents->links() }}
    </div>
</div>

@endsection