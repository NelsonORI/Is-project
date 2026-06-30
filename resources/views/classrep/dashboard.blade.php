@extends('layouts.app')
@section('title', 'Class Rep Dashboard')

@section('content')

{{-- Stats Row --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
        <p class="text-sm text-gray-500 mb-1">Flashcard sets available</p>
        <p class="text-3xl font-bold text-gray-800">{{ $totalSets }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
        <p class="text-sm text-gray-500 mb-1">Papers uploaded</p>
        <p class="text-3xl font-bold text-gray-800">{{ $papersUploaded }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
        <p class="text-sm text-gray-500 mb-1">Units covered</p>
        <p class="text-3xl font-bold text-gray-800">{{ $unitsCovered }}</p>
    </div>
</div>

{{-- Quick Actions --}}
<div class="bg-white border border-gray-200 rounded-xl px-6 py-5 mb-6">
    <h2 class="text-sm font-semibold text-gray-700 mb-4">Quick Actions</h2>
    <div class="flex gap-3">
        <a href="{{ route('classrep.upload.step1') }}"
            class="bg-su-blue hover:bg-su-blue/90 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
            + Upload Past Paper
        </a>
        <a href="#"
            class="border border-gray-300 text-gray-600 text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-gray-50 transition">
            View My Uploads
        </a>
    </div>
</div>

{{-- Recent Uploads --}}
@if($recentUploads->isNotEmpty())
<div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
    <h2 class="text-sm font-semibold text-gray-700 mb-4">My Recent Uploads</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[600px]">
            <thead>
                <tr class="text-xs text-gray-400 border-b border-gray-100">
                    <th class="text-left pb-2 font-medium">Filename</th>
                    <th class="text-left pb-2 font-medium">Unit</th>
                    <th class="text-left pb-2 font-medium">Exam Type</th>
                    <th class="text-left pb-2 font-medium">Cards</th>
                    <th class="text-left pb-2 font-medium">Uploaded</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentUploads as $document)
                    @php
                        $meta      = $document->flashcards->first()?->metadata;
                        $cardCount = $document->flashcards->count();
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-3 text-gray-700 max-w-xs truncate">
                            {{ $document->original_filename }}
                        </td>
                        <td class="py-3">
                            <span class="bg-su-blue-light text-su-blue text-xs font-semibold px-2 py-0.5 rounded">
                                {{ $meta->unit_code ?? '—' }}
                            </span>
                        </td>
                        <td class="py-3 text-gray-500">{{ $meta->exam_type ?? '—' }}</td>
                        <td class="py-3 text-gray-500">{{ $cardCount }} cards</td>
                        <td class="py-3 text-gray-400 text-xs">
                            {{ \Carbon\Carbon::parse($document->uploaded_at)->format('d M Y') }}
                        </td>
                        <td class="py-3">
                            <a href="{{ route('student.study', $document->id) }}"
                                class="text-su-blue hover:underline text-xs font-medium">
                                Study
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection