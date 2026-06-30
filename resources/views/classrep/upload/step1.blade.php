@extends('layouts.app')
@section('title', 'Upload Paper — Step 1')

@section('content')
<div class="max-w-2xl mx-auto">

    @include('classrep.upload.partials.stepper', ['currentStep' => 1])

    <div class="bg-white border border-gray-200 rounded-2xl p-8">

        <h2 class="text-lg font-semibold text-gray-800 mb-1">Upload Past Paper</h2>
        <p class="text-sm text-gray-400 mb-6">PDF files only. Maximum file size is 20MB.</p>

        @if($errors->any())
            <div class="mb-5 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('classrep.upload.step1') }}" enctype="multipart/form-data">
            @csrf

            {{-- Drop zone --}}
            <div id="dropzone"
                class="border-2 border-dashed border-gray-300 rounded-xl p-10 text-center cursor-pointer hover:border-su-blue hover:bg-su-blue-light transition mb-6"
                onclick="document.getElementById('pdf-input').click()">

                <div id="dropzone-idle">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-sm text-gray-500 font-medium">Drag & drop your PDF here</p>
                    <p class="text-xs text-gray-400 mt-1">or click to browse files</p>
                </div>

                <div id="dropzone-selected" class="hidden">
                    <svg class="w-10 h-10 text-su-blue mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p id="selected-filename" class="text-sm font-medium text-indigo-700"></p>
                    <p id="selected-filesize" class="text-xs text-gray-400 mt-1"></p>
                </div>

            </div>

            <input type="file" id="pdf-input" name="pdf" accept=".pdf" class="hidden">

            <button type="submit" id="submit-btn"
                class="w-full bg-su-blue hover:bg-su-blue/90 text-white font-semibold py-2.5 rounded-lg text-sm transition disabled:opacity-50 disabled:cursor-not-allowed">
                Continue to Metadata →
            </button>

        </form>
    </div>
</div>

<script>
    const input      = document.getElementById('pdf-input');
    const dropzone   = document.getElementById('dropzone');
    const idle       = document.getElementById('dropzone-idle');
    const selected   = document.getElementById('dropzone-selected');
    const filename   = document.getElementById('selected-filename');
    const filesize   = document.getElementById('selected-filesize');

    function showFile(file) {
        if (!file || file.type !== 'application/pdf') return;
        idle.classList.add('hidden');
        selected.classList.remove('hidden');
        filename.textContent = file.name;
        filesize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    }

    input.addEventListener('change', () => showFile(input.files[0]));

    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('border-indigo-400', 'bg-indigo-50');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('border-indigo-400', 'bg-indigo-50');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        const file = e.dataTransfer.files[0];
        input.files = e.dataTransfer.files;
        showFile(file);
    });
</script>

@endsection