@extends('layouts.app')

@section('title', 'Dashboard — Strathmore Flashcards')

@section('content')

{{-- ==================== STATS ROW ==================== --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
        <p class="text-sm text-gray-500 mb-1">Flashcard sets available</p>
        <p class="text-3xl font-bold text-gray-800">{{ $totalSets }}</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
        <p class="text-sm text-gray-500 mb-1">Sets studied this week</p>
        <p class="text-3xl font-bold text-gray-800">{{ $studiedThisWeek }}</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
        <p class="text-sm text-gray-500 mb-1">Units covered</p>
        <p class="text-3xl font-bold text-gray-800">{{ $unitsCovered }}</p>
    </div>

</div>

{{-- ==================== FILTER BAR ==================== --}}
<div class="bg-white border border-gray-200 rounded-xl px-6 py-5 mb-6">

    <h2 class="text-sm font-semibold text-gray-700 mb-4">Find flashcards</h2>

    <form method="GET" action="{{ route('student.dashboard') }}">

        {{-- Dropdowns row --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">

            {{-- Unit Code --}}
            <div>
                <label class="block text-xs text-gray-400 mb-1">Unit code</label>
                <select name="unit_code"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">All units</option>
                    @foreach($unitCodes as $code)
                        <option value="{{ $code }}" {{ request('unit_code') === $code ? 'selected' : '' }}>
                            {{ $code }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Lecturer --}}
            <div>
                <label class="block text-xs text-gray-400 mb-1">Lecturer</label>
                <select name="lecturer"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">All lecturers</option>
                    @foreach($lecturers as $lec)
                        <option value="{{ $lec }}" {{ request('lecturer') === $lec ? 'selected' : '' }}>
                            {{ $lec }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Exam Type --}}
            <div>
                <label class="block text-xs text-gray-400 mb-1">Exam type</label>
                <select name="exam_type"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">Mid / Final / Quiz</option>
                    @foreach($examTypes as $type)
                        <option value="{{ $type }}" {{ request('exam_type') === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Year --}}
            <div>
                <label class="block text-xs text-gray-400 mb-1">Year</label>
                <select name="year"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">All years</option>
                    @foreach($years as $yr)
                        <option value="{{ $yr }}" {{ request('year') === $yr ? 'selected' : '' }}>
                            {{ $yr }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        {{-- Keyword search + buttons --}}
        <div class="flex flex-col sm:flex-row gap-2">
            <input
                type="text"
                name="keyword"
                value="{{ request('keyword') }}"
                placeholder="Search by unit name, topic or keyword..."
                class="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
            >
            <button type="submit"
                class="bg-su-blue hover:bg-su-blue/90 text-white text-sm font-semibold px-5 py-2 rounded-lg flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                </svg>
                Search
            </button>
            <a href="{{ route('student.dashboard') }}"
                class="border border-gray-300 text-gray-600 text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                Clear
            </a>
        </div>

    </form>

</div>

{{-- ==================== NO RESULTS + RECOMMENDATIONS ==================== --}}
@if(request()->hasAny(['unit_code', 'lecturer', 'exam_type', 'year', 'keyword']) && !$exactMatchFound)
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-6 py-4 mb-6 text-sm text-amber-800">
        No exact match found for your search. Showing recommended alternatives below.
    </div>
@endif

{{-- ==================== FLASHCARD GRID ==================== --}}
<div class="flex items-center justify-between mb-3">
    <h2 class="text-sm font-semibold text-gray-700">
        {{ request()->hasAny(['unit_code', 'lecturer', 'exam_type', 'year', 'keyword']) && !$exactMatchFound
            ? 'Recommended flashcard sets'
            : 'Recent flashcard sets' }}
    </h2>
    <span class="text-xs text-gray-400">
        Showing {{ $flashcardSets->count() }} of {{ $flashcardSets->total() }}
    </span>
</div>

@php
    $displaySets = $exactMatchFound ? $flashcardSets : $recommendations;
@endphp

@if($displaySets->isEmpty())
    <div class="bg-white border border-gray-200 rounded-xl px-6 py-12 text-center">
        <p class="text-gray-400 text-sm">No flashcard sets found. Try adjusting your search filters.</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($displaySets as $document)
            @php
                $firstCard = $document->flashcards->first();
                $meta      = $firstCard?->metadata;
                $cardCount = $document->flashcards->count();
            @endphp

            <div class="bg-white border border-gray-200 rounded-xl p-5 flex flex-col justify-between hover:shadow-sm transition">

                {{-- Badges --}}
                <div class="flex items-center gap-2 mb-3">
                    @if($meta)
                        <span class="bg-su-blue-light text-su-blue text-xs font-semibold px-2 py-0.5 rounded">
                            {{ $meta->unit_code }}
                        </span>
                        <span class="bg-su-gold-light text-su-gold text-xs font-medium px-2 py-0.5 rounded">
                            {{ $meta->exam_type }}
                        </span>
                    @endif
                </div>

                {{-- Title & Meta --}}
                <div class="mb-3">
                    <h3 class="font-semibold text-gray-800 text-sm mb-0.5">{{ $document->original_filename }}</h3>
                    @if($meta)
                        <p class="text-xs text-gray-400">
                            {{ $meta->lecturer }} · {{ $meta->academic_year }} · {{ $meta->semester }}
                        </p>
                    @endif
                </div>

                {{-- Question Preview --}}
                @if($firstCard)
                    <p class="text-xs text-gray-500 border-l-2 border-gray-200 pl-3 mb-4 line-clamp-2">
                        Q: {{ Str::limit($firstCard->question, 80) }}
                    </p>
                @endif

                {{-- Footer --}}
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        {{ $cardCount }} cards
                    </span>
                    <a href="{{ route('student.study', $document->id) }}"
                        class="bg-su-blue hover:bg-su-blue/90 text-white text-xs font-semibold px-4 py-1.5 rounded-lg transition">
                        Study
                    </a>
                </div>

            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($exactMatchFound)
        <div class="mt-6">
            {{ $flashcardSets->withQueryString()->links() }}
        </div>
    @endif

@endif

@endsection
