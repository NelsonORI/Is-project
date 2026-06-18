@extends('layouts.auth')

@section('title', 'Register')
@section('subtitle', 'Create your student account')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="mb-5 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Name --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror"
                placeholder="John Doe"
            >
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Strathmore Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror"
                placeholder="you@strathmore.edu"
            >
            <p class="text-xs text-gray-400 mt-1">Only @strathmore.edu addresses are accepted.</p>
        </div>

        {{-- Student Number --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Student Number</label>
            <input
                type="text"
                name="student_number"
                value="{{ old('student_number') }}"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('student_number') border-red-400 @enderror"
                placeholder="e.g. 123456"
            >
        </div>

        {{-- School --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">School</label>
            <input
                type="text"
                name="school"
                value="{{ old('school') }}"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('school') border-red-400 @enderror"
                placeholder="e.g. School of Computing"
            >
        </div>

        {{-- Programme --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Programme</label>
            <input
                type="text"
                name="programme"
                value="{{ old('programme') }}"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('programme') border-red-400 @enderror"
                placeholder="e.g. BSc. Computer Science"
            >
        </div>

        {{-- Year of Study --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Year of Study</label>
            <select
                name="year_of_study"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('year_of_study') border-red-400 @enderror"
            >
                <option value="">Select year</option>
                @foreach(range(1, 6) as $year)
                    <option value="{{ $year }}" {{ old('year_of_study') == $year ? 'selected' : '' }}>
                        Year {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Password --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input
                type="password"
                name="password"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 @enderror"
                placeholder="Minimum 8 characters"
            >
        </div>

        {{-- Confirm Password --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input
                type="password"
                name="password_confirmation"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Repeat your password"
            >
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg text-sm transition"
        >
            Create Account
        </button>

    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Already have an account?
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Sign in</a>
    </p>

</div>

@endsection