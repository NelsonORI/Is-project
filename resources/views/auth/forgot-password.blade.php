@extends('layouts.auth')

@section('title', 'Forgot Password')
@section('subtitle', 'Reset your account password')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

    {{-- Success message --}}
    @if(session('status'))
        <div class="mb-5 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

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

    <p class="text-sm text-gray-500 mb-6">
        Enter your Strathmore email address and we will send you a link to reset your password.
    </p>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Strathmore Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-su-blue @error('email') border-red-400 @enderror"
                placeholder="you@strathmore.edu"
            >
        </div>

        {{-- Submit --}}
        <button type="submit"
            style="background-color: #2D3092;"
            class="w-full text-white font-semibold py-2.5 rounded-lg text-sm transition hover:opacity-90">
            Send Reset Link
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