@extends('layouts.auth')

@section('title', 'Verify Email')
@section('subtitle', 'One more step before you get started')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center">

    <div class="mb-6">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-lg font-semibold text-gray-800">Check your inbox</h2>
        <p class="text-sm text-gray-500 mt-2">
            We sent a verification link to
            <span class="font-medium text-gray-700">{{ auth()->guard('student')->user()->email }}</span>.
            Click the link in the email to activate your account.
        </p>
    </div>

    {{-- Resend form --}}
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg text-sm transition"
        >
            Resend Verification Email
        </button>
    </form>

    {{-- Logout link --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="text-sm text-gray-400 hover:text-gray-600 hover:underline">
            Sign out and use a different account
        </button>
    </form>

</div>

@endsection