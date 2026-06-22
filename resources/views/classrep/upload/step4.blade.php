@extends('layouts.app')
@section('title', 'Upload Complete')

@section('content')
<div class="max-w-2xl mx-auto">

    @include('classrep.upload.partials.stepper', ['currentStep' => 4])

    <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center">

        {{-- Success icon --}}
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-1">Upload Successful!</h2>
        <p class="text-sm text-gray-400 mb-8">Your past paper has been processed and is now live.</p>

        {{-- Summary card --}}
        <div class="bg-gray-50 border border-gray-200 rounded-xl px-6 py-5 text-left max-w-sm mx-auto mb-8 space-y-3">

            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Unit</span>
                <span class="font-medium text-gray-800">
                    {{ $summary['unit_code'] }} — {{ $summary['unit_name'] }}
                </span>
            </div>

            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Exam type</span>
                <span class="font-medium text-gray-800">{{ $summary['exam_type'] }}</span>
            </div>

            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Flashcards generated</span>
                <span class="font-semibold text-indigo-600">{{ $summary['card_count'] }} cards</span>
            </div>

            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Uploaded at</span>
                <span class="font-medium text-gray-800">{{ $summary['uploaded_at'] }}</span>
            </div>

        </div>

        {{-- Actions --}}
        <div class="flex gap-3 justify-center">
            <a href="#"
                class="border border-gray-300 text-gray-600 font-medium px-5 py-2.5 rounded-lg text-sm hover:bg-gray-50 transition">
                View Flashcards
            </a>
            <a href="{{ route('classrep.upload.step1') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition">
                Upload Another
            </a>
        </div>

    </div>
</div>
@endsection