@extends('layouts.app')
@section('title', 'Upload Paper — Processing')

@section('content')
<div class="max-w-2xl mx-auto">

    @include('classrep.upload.partials.stepper', ['currentStep' => 3])

    <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center">

        {{-- Spinner --}}
        <div id="processing-state">
            <div class="w-16 h-16 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin mx-auto mb-6"></div>
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Processing your paper</h2>
            <p class="text-sm text-gray-400 mb-8">This may take a minute. Please don't close this page.</p>

            {{-- Pipeline steps --}}
            <div class="text-left max-w-sm mx-auto space-y-3">

                <div class="pipeline-step flex items-center gap-3" id="step-upload">
                    <div class="step-dot w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0"></div>
                    <span class="step-text text-sm text-gray-500">Uploading PDF to OCR engine</span>
                </div>

                <div class="pipeline-step flex items-center gap-3" id="step-ocr">
                    <div class="step-dot w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0"></div>
                    <span class="step-text text-sm text-gray-500">Running Tesseract OCR</span>
                </div>

                <div class="pipeline-step flex items-center gap-3" id="step-ai">
                    <div class="step-dot w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0"></div>
                    <span class="step-text text-sm text-gray-500">Generating flashcards with Groq AI</span>
                </div>

                <div class="pipeline-step flex items-center gap-3" id="step-save">
                    <div class="step-dot w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0"></div>
                    <span class="step-text text-sm text-gray-500">Saving flashcards to database</span>
                </div>

            </div>
        </div>

        {{-- Error state --}}
        <div id="error-state" class="hidden">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Processing failed</h2>
            <p id="error-message" class="text-sm text-red-500 mb-6"></p>
            <a href="{{ route('classrep.upload.step1') }}"
                class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition">
                Try Again
            </a>
        </div>

    </div>
</div>

<script>
    const steps = ['step-upload', 'step-ocr', 'step-ai', 'step-save'];
    let current  = 0;

    function activateStep(id) {
        const el = document.getElementById(id);
        if (!el) return;
        const dot = el.querySelector('.step-dot');
        const txt = el.querySelector('.step-text');
        if (dot) {
            dot.classList.remove('border-gray-300');
            dot.classList.add('border-indigo-500', 'bg-indigo-100');
        }
        if (txt) {
            txt.classList.remove('text-gray-500');
            txt.classList.add('text-indigo-700', 'font-medium');
        }
    }

    function completeStep(id) {
        const el = document.getElementById(id);
        if (!el) return;
        const dot = el.querySelector('.step-dot');
        if (dot) {
            dot.classList.remove('border-gray-300', 'border-indigo-500', 'bg-indigo-100');
            dot.classList.add('border-green-500', 'bg-green-100');
            dot.innerHTML = `<svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
            </svg>`;
        }
    }

    function showError(message) {
        const ps = document.getElementById('processing-state');
        const es = document.getElementById('error-state');
        const em = document.getElementById('error-message');
        if (ps) ps.classList.add('hidden');
        if (es) es.classList.remove('hidden');
        if (em) em.textContent = message;
    }

    const interval = setInterval(() => {
        if (current < steps.length) {
            if (current > 0) completeStep(steps[current - 1]);
            activateStep(steps[current]);
            current++;
        } else {
            clearInterval(interval);
        }
    }, 800);

    setTimeout(function() {
        fetch('{{ route("classrep.upload.process") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            console.log('Response:', data);
            if (data.success === true) {
                clearInterval(interval);
                steps.forEach(function(s) { completeStep(s); });
                setTimeout(function() {
                    window.location.href = '{{ route("classrep.upload.step4") }}';
                }, 1000);
            } else {
                clearInterval(interval);
                showError(data.error || 'Something went wrong. Please try again.');
            }
        })
        .catch(function(error) {
            console.log('Fetch error:', error);
            clearInterval(interval);
            showError('Request failed: ' + error.message);
        });
    }, 1500);
</script>

@endsection