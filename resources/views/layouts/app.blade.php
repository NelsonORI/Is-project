<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Strathmore Flashcards')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'su-blue':   '#2D3092',
                        'su-gold':   '#D4A017',
                        'su-red':    '#E8402A',
                        'su-blue-light': '#E8E9F5',
                        'su-gold-light': '#FBF3DC',
                        'su-red-light':  '#FDECEA',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800">

@php $student = Auth::guard('student')->user(); @endphp

{{-- Mobile top bar --}}
<div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between sticky top-0 z-30">
    <div class="flex items-center gap-2">
        <img src="{{ asset('images/logo.png') }}" alt="Strathmore University" class="h-7 w-auto">
        <span class="font-bold text-gray-800 text-sm">Strathmore Flashcards</span>
    </div>
    <div class="flex items-center gap-3">
        <button class="text-amber-400">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zm0 16a2 2 0 01-2-2h4a2 2 0 01-2 2z"/>
            </svg>
        </button>
        <div class="w-8 h-8 rounded-full bg-su-blue text-white text-xs font-bold flex items-center justify-center">
            {{ strtoupper(substr($student->name, 0, 2)) }}
        </div>
        <button onclick="toggleSidebar()" class="text-gray-600 focus:outline-none">
            <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
</div>

{{-- Overlay --}}
<div id="sidebar-overlay"
    class="hidden fixed inset-0 bg-black/40 z-30 lg:hidden"
    onclick="toggleSidebar()">
</div>

<div class="flex h-screen lg:h-auto min-h-screen overflow-hidden lg:overflow-visible">

    {{-- Sidebar --}}
    <aside id="sidebar"
        class="fixed top-0 left-0 h-full w-64 bg-white border-r border-gray-200 flex flex-col z-40
               transform -translate-x-full transition-transform duration-300 ease-in-out
               lg:relative lg:translate-x-0 lg:w-52 lg:flex-shrink-0">

        {{-- Logo --}}
        <div class="px-5 py-5 border-b border-gray-100 hidden lg:block">
            <img src="{{ asset('images/logo.png') }}" alt="Strathmore University" class="h-9 w-auto">
            <span class="font-bold text-gray-800 text-base">Strathmore Flashcards</span>
        </div>

        {{-- Close button (mobile only) --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 lg:hidden">
            <span class="font-bold text-gray-800 text-base">Menu</span>
            <button onclick="toggleSidebar()" class="text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-5 space-y-6 overflow-y-auto">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-2 mb-2">Menu</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ $student->role === 'class_rep' ? route('classrep.dashboard') : route('student.dashboard') }}"
                           onclick="closeSidebar()"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                           {{ request()->routeIs('student.dashboard') || request()->routeIs('classrep.dashboard') ? 'bg-su-blue-light text-su-blue' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span>📊</span> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.dashboard') }}"
                           onclick="closeSidebar()"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">
                            <span>🔍</span> Browse flashcards
                        </a>
                    </li>

                    @if($student->role === 'class_rep')
                        <li class="pt-1 border-t border-gray-100 mt-2">
                            <a href="{{ route('classrep.upload.step1') }}"
                               onclick="closeSidebar()"
                               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                               {{ request()->routeIs('classrep.upload*') ? 'bg-su-blue-light text-su-blue' : 'text-gray-600 hover:bg-gray-100' }}">
                                <span>📤</span> Upload Paper
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>

        {{-- Logout --}}
        <div class="px-3 py-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-sm font-medium text-su-red hover:bg-su-red-light transition">
                    <span>🚪</span> Log out
                </button>
            </form>
        </div>

    </aside>

    {{-- Main area --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Desktop top bar --}}
        <header class="hidden lg:flex bg-white border-b border-gray-200 px-6 py-3 items-center justify-between flex-shrink-0">
            <div>
                <span class="inline-block border border-gray-300 text-gray-600 text-xs font-medium px-3 py-1 rounded-full">
                    {{ $student->role === 'class_rep' ? 'Class Rep' : 'Student' }}
                </span>
            </div>
            <div class="flex items-center gap-4">
                <button class="relative text-amber-400 hover:text-amber-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zm0 16a2 2 0 01-2-2h4a2 2 0 01-2 2z"/>
                    </svg>
                </button>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-700">{{ $student->name }}</span>
                    <div class="w-8 h-8 rounded-full bg-su-blue text-white text-xs font-bold flex items-center justify-center">
                        {{ strtoupper(substr($student->name, 0, 2)) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto px-4 py-4 lg:px-6 lg:py-6">
            @yield('content')
        </main>

    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar  = document.getElementById('sidebar');
        const overlay  = document.getElementById('sidebar-overlay');
        const isOpen   = !sidebar.classList.contains('-translate-x-full');

        if (isOpen) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        } else {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }
    }

    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }
</script>

</body>
</html>