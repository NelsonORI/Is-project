<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Strathmore Flashcards</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center">

    <div class="w-full max-w-md px-6 py-10">

        {{-- Logo & Heading --}}
        <div class="text-center mb-8">
            <img
                src="{{ asset('images/strathmore-logo.png') }}"
                alt="Strathmore University"
                class="h-16 mx-auto mb-4"
                onerror="this.style.display='none'"
            >
            <h1 class="text-2xl font-bold text-gray-800">Strathmore Flashcards</h1>
            <p class="text-sm text-gray-500 mt-1">@yield('subtitle')</p>
        </div>

        {{-- Flash Messages --}}
        @if(session('resent'))
            <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded text-sm">
                A fresh verification link has been sent to your email.
            </div>
        @endif

        @if(session('verified'))
            <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded text-sm">
                Your email has been verified successfully.
            </div>
        @endif

        {{-- Page Content --}}
        @yield('content')

    </div>

</body>
</html>