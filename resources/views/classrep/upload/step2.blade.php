@extends('layouts.app')
@section('title', 'Upload Paper — Step 2')

@section('content')
<div class="max-w-2xl mx-auto">

    @include('classrep.upload.partials.stepper', ['currentStep' => 2])

    <div class="bg-white border border-gray-200 rounded-2xl p-8">

        <h2 class="text-lg font-semibold text-gray-800 mb-1">Tag Metadata</h2>
        <p class="text-sm text-gray-400 mb-6">
            These tags help students find this paper when searching.
        </p>

        @if($errors->any())
            <div class="mb-5 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('classrep.upload.step2') }}" class="space-y-5">
            @csrf

            {{-- Unit Code + Unit Name --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Code</label>
                    <input type="text" name="unit_code" value="{{ old('unit_code') }}"
                        placeholder="e.g. CS 2101"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('unit_code') border-red-400 @enderror">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Name</label>
                    <input type="text" name="unit_name" value="{{ old('unit_name') }}"
                        placeholder="e.g. Data Structures"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('unit_name') border-red-400 @enderror">
                </div>
            </div>

            {{-- Lecturer --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lecturer</label>
                <input type="text" name="lecturer" value="{{ old('lecturer') }}"
                    placeholder="e.g. Dr. Omondi"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('lecturer') border-red-400 @enderror">
            </div>

            {{-- Academic Year + Semester --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                    <input type="text" name="academic_year" value="{{ old('academic_year') }}"
                        placeholder="e.g. 2023/2024"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('academic_year') border-red-400 @enderror">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                    <select name="semester"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('semester') border-red-400 @enderror">
                        <option value="">Select semester</option>
                        @foreach(['Semester 1','Semester 2','Trimester 1','Trimester 2','Trimester 3'] as $sem)
                            <option value="{{ $sem }}" {{ old('semester') === $sem ? 'selected' : '' }}>
                                {{ $sem }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Exam Type --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Exam Type</label>
                <div class="grid grid-cols-4 gap-3">
                    @foreach(['Mid-term','Final','Quiz','CAT'] as $type)
                        <label class="flex items-center justify-center border rounded-lg py-2 text-sm cursor-pointer
                            {{ old('exam_type') === $type ? 'border-indigo-500 bg-indigo-50 text-indigo-700 font-medium' : 'border-gray-200 text-gray-600 hover:border-indigo-300' }}">
                            <input type="radio" name="exam_type" value="{{ $type }}" class="hidden"
                                {{ old('exam_type') === $type ? 'checked' : '' }}>
                            {{ $type }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('classrep.upload.step1') }}"
                    class="flex-1 text-center border border-gray-300 text-gray-600 font-medium py-2.5 rounded-lg text-sm hover:bg-gray-50 transition">
                    ← Back
                </a>
                <button type="submit"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg text-sm transition">
                    Start Processing →
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    // Highlight selected exam type radio
    document.querySelectorAll('input[name="exam_type"]').forEach(radio => {
        radio.addEventListener('change', () => {
            document.querySelectorAll('input[name="exam_type"]').forEach(r => {
                r.parentElement.classList.remove('border-indigo-500', 'bg-indigo-50', 'text-indigo-700', 'font-medium');
                r.parentElement.classList.add('border-gray-200', 'text-gray-600');
            });
            radio.parentElement.classList.add('border-indigo-500', 'bg-indigo-50', 'text-indigo-700', 'font-medium');
            radio.parentElement.classList.remove('border-gray-200', 'text-gray-600');
        });
    });
</script>

@endsection