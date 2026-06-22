@extends('layouts.app')
@section('title', 'Class Rep Dashboard')

@section('content')

{{-- Stats Row --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
        <p class="text-sm text-gray-500 mb-1">Flashcard sets available</p>
        <p class="text-3xl font-bold text-gray-800">0</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
        <p class="text-sm text-gray-500 mb-1">Papers uploaded</p>
        <p class="text-3xl font-bold text-gray-800">0</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
        <p class="text-sm text-gray-500 mb-1">Units covered</p>
        <p class="text-3xl font-bold text-gray-800">0</p>
    </div>
</div>

{{-- Quick actions --}}
<div class="bg-white border border-gray-200 rounded-xl px-6 py-5 mb-6">
    <h2 class="text-sm font-semibold text-gray-700 mb-4">Quick Actions</h2>
    <div class="flex gap-3">
        <a href="{{ route('classrep.upload.step1') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
            + Upload Past Paper
        </a>
        <a href="#"
            class="border border-gray-300 text-gray-600 text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-gray-50 transition">
            View My Uploads
        </a>
    </div>
</div>

@endsection