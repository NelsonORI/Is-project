@extends('layouts.app')
@section('title', 'Study — ' . ($metadata->unit_code ?? ''))

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('student.dashboard') }}"
                class="text-sm text-gray-400 hover:text-gray-600 flex items-center gap-1 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Dashboard
            </a>
            <h1 class="text-lg font-bold text-gray-800">
                {{ $metadata->unit_code ?? '' }} — {{ $metadata->exam_type ?? '' }}
            </h1>
            <p class="text-sm text-gray-400">
                {{ $metadata->lecturer ?? '' }} · {{ $metadata->academic_year ?? '' }} · {{ $metadata->semester ?? '' }}
            </p>
        </div>

        {{-- Progress --}}
        <div class="text-right">
            <span class="text-sm font-semibold text-indigo-600" id="progress-text">
                Card 1 of {{ $flashcards->count() }}
            </span>
            <div class="w-40 h-1.5 bg-gray-200 rounded-full mt-1.5">
                <div id="progress-bar"
                    class="h-1.5 bg-indigo-500 rounded-full transition-all duration-300"
                    style="width: {{ round(1 / $flashcards->count() * 100) }}%">
                </div>
            </div>
        </div>
    </div>

    {{-- Flashcard --}}
    <div class="perspective mb-6" style="perspective: 1000px;">
        <div id="flashcard"
            class="relative w-full cursor-pointer transition-transform duration-500"
            style="transform-style: preserve-3d; min-height: 320px;"
            onclick="flipCard()">

            {{-- Front — Question --}}
            <div class="absolute inset-0 bg-white border border-gray-200 rounded-2xl p-10 flex flex-col items-center justify-center"
                style="backface-visibility: hidden;">
                <span class="text-xs font-semibold text-indigo-400 uppercase tracking-widest mb-6">Question</span>
                <p id="question-text" class="text-center text-gray-800 text-lg font-medium leading-relaxed"></p>
                <p class="text-xs text-gray-300 mt-8">Click card to reveal answer</p>
            </div>

            {{-- Back — Answer --}}
            <div class="absolute inset-0 bg-indigo-600 border border-indigo-700 rounded-2xl p-10 flex flex-col items-center justify-center"
                style="backface-visibility: hidden; transform: rotateY(180deg);">
                <span class="text-xs font-semibold text-indigo-200 uppercase tracking-widest mb-6">Answer</span>
                <p id="answer-text" class="text-center text-white text-base leading-relaxed"></p>
            </div>

        </div>
    </div>

    {{-- Navigation --}}
    <div class="flex items-center justify-between">

        <button onclick="prevCard()"
            id="btn-prev"
            class="flex items-center gap-2 border border-gray-300 text-gray-600 font-medium px-5 py-2.5 rounded-lg text-sm hover:bg-gray-50 transition disabled:opacity-30 disabled:cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Previous
        </button>

        {{-- Dot indicators --}}
        <div class="flex gap-1.5" id="dot-indicators">
            @foreach($flashcards as $index => $card)
                <div class="w-2 h-2 rounded-full transition-all duration-200
                    {{ $index === 0 ? 'bg-indigo-500 w-4' : 'bg-gray-300' }}
                    dot-indicator">
                </div>
            @endforeach
        </div>

        <button onclick="nextCard()"
            id="btn-next"
            class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition">
            Next
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

    </div>

    {{-- Completion screen --}}
    <div id="completion-screen" class="hidden mt-6">
        <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-1">You've completed this set!</h2>
            <p class="text-sm text-gray-400 mb-6">
                You went through all {{ $flashcards->count() }} cards in
                {{ $metadata->unit_code }} — {{ $metadata->exam_type }}.
            </p>
            <div class="flex gap-3 justify-center">
                <button onclick="restartStudy()"
                    class="border border-gray-300 text-gray-600 font-medium px-5 py-2.5 rounded-lg text-sm hover:bg-gray-50 transition">
                    Study Again
                </button>
                <a href="{{ route('student.dashboard') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

</div>

{{-- Flashcard data --}}
<script>
    const flashcards = @json($flashcards->map(fn($f) => [
        'question' => $f->question,
        'answer'   => $f->answer,
    ]));

    let current  = 0;
    let flipped  = false;
    const total  = flashcards.length;

    function loadCard(index) {
        // Reset flip
        flipped = false;
        document.getElementById('flashcard').style.transform = 'rotateY(0deg)';

        // Update content
        document.getElementById('question-text').textContent = flashcards[index].question;
        document.getElementById('answer-text').textContent   = flashcards[index].answer;

        // Update progress
        document.getElementById('progress-text').textContent = `Card ${index + 1} of ${total}`;
        const pct = Math.round((index + 1) / total * 100);
        document.getElementById('progress-bar').style.width = pct + '%';

        // Update dots
        document.querySelectorAll('.dot-indicator').forEach((dot, i) => {
            dot.classList.remove('bg-indigo-500', 'w-4', 'bg-gray-300');
            if (i === index) {
                dot.classList.add('bg-indigo-500', 'w-4');
            } else if (i < index) {
                dot.classList.add('bg-indigo-500');
            } else {
                dot.classList.add('bg-gray-300');
            }
        });

        // Update buttons
        document.getElementById('btn-prev').disabled = index === 0;
    }

    function flipCard() {
        flipped = !flipped;
        document.getElementById('flashcard').style.transform = flipped
            ? 'rotateY(180deg)'
            : 'rotateY(0deg)';
    }

    function nextCard() {
        if (current < total - 1) {
            current++;
            loadCard(current);
        } else {
            // Show completion screen
            document.getElementById('flashcard').closest('.perspective').classList.add('hidden');
            document.querySelector('.flex.items-center.justify-between:last-of-type').classList.add('hidden');
            document.getElementById('completion-screen').classList.remove('hidden');
        }
    }

    function prevCard() {
        if (current > 0) {
            current--;
            loadCard(current);
        }
    }

    function restartStudy() {
        current = 0;
        document.getElementById('flashcard').closest('.perspective').classList.remove('hidden');
        document.querySelector('.flex.items-center.justify-between:last-of-type').classList.remove('hidden');
        document.getElementById('completion-screen').classList.add('hidden');
        loadCard(0);
    }

    // Load first card on page load
    loadCard(0);
</script>

@endsection