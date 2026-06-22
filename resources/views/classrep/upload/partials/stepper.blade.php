@php
    $steps = [
        1 => 'Upload PDF',
        2 => 'Tag Metadata',
        3 => 'Processing',
        4 => 'Done',
    ];
@endphp

<div class="flex items-center justify-center mb-10">
    @foreach($steps as $number => $label)

        {{-- Step circle --}}
        <div class="flex flex-col items-center">
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold
                {{ $currentStep == $number
                    ? 'bg-indigo-600 text-white'
                    : ($currentStep > $number
                        ? 'bg-green-500 text-white'
                        : 'bg-gray-200 text-gray-400') }}">
                @if($currentStep > $number)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                @else
                    {{ $number }}
                @endif
            </div>
            <span class="text-xs mt-1 font-medium
                {{ $currentStep == $number ? 'text-indigo-600' : 'text-gray-400' }}">
                {{ $label }}
            </span>
        </div>

        {{-- Connector line --}}
        @if(!$loop->last)
            <div class="h-px w-16 mb-4
                {{ $currentStep > $number ? 'bg-green-400' : 'bg-gray-200' }}">
            </div>
        @endif

    @endforeach
</div>