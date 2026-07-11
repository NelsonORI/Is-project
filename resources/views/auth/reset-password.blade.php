@extends('layouts.auth')

@section('title', 'Reset Password')
@section('subtitle', 'Create a new password for your account')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="mb-5 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf

        {{-- Token --}}
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Strathmore Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email', $email) }}"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-su-blue @error('email') border-red-400 @enderror"
                placeholder="you@strathmore.edu"
            >
        </div>

        {{-- New Password --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
            <input
                type="password"
                name="password"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-su-blue @error('password') border-red-400 @enderror"
                placeholder="Minimum 8 characters"
            >
        </div>

        {{-- Confirm Password --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
            <input
                type="password"
                name="password_confirmation"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-su-blue"
                placeholder="Repeat your new password"
            >
        </div>

        {{-- Submit --}}
        <button type="submit"
            style="background-color: #2D3092;"
            class="w-full text-white font-semibold py-2.5 rounded-lg text-sm transition hover:opacity-90">
            Reset Password
        </button>

    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Remembered your password?
        <a href="{{ route('login') }}" class="font-medium hover:underline" style="color: #2D3092;">
            Sign in
        </a>
    </p>

</div>

@endsection