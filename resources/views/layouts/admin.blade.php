<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin — Strathmore Flashcards')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800 flex h-screen overflow-hidden">

    {{-- ==================== SIDEBAR ==================== --}}
    <aside class="w-52 bg-white border-r border-gray-200 flex flex-col flex-shrink-0">

        {{-- Logo --}}
        <div class="px-5 py-5 border-b border-gray-100">
            <span class="font-bold text-gray-800 text-base">Strathmore Flashcards</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-5 space-y-6 overflow-y-auto">

            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-2 mb-2">Menu</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                           {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span>📊</span> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                           {{ request()->routeIs('admin.users') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span>👥</span> Users
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.documents') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                           {{ request()->routeIs('admin.documents') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span>📄</span> Documents
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.classreps') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                           {{ request()->routeIs('admin.classreps') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span>✅</span> Class Reps
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-2 mb-2">Reports</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.gap-report') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                           {{ request()->routeIs('admin.gap-report') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span>📈</span> Gap Report
                        </a>
                    </li>
                </ul>
            </div>

        </nav>

        {{-- Logout --}}
        <div class="px-3 py-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50 transition">
                    <span>🚪</span> Log out
                </button>
            </form>
        </div>

    </aside>

    {{-- ==================== MAIN AREA ==================== --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top Bar --}}
        <header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between flex-shrink-0">

            <div>
                <span class="inline-block border border-gray-300 text-gray-600 text-xs font-medium px-3 py-1 rounded-full">
                    Admin
                </span>
            </div>

            <div class="flex items-center gap-4">
                <button class="relative text-amber-400 hover:text-amber-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zm0 16a2 2 0 01-2-2h4a2 2 0 01-2 2z"/>
                    </svg>
                </button>

                <div class="flex items-center gap-2">
                    @php $admin = Auth::guard('admin')->user(); @endphp
                    <span class="text-sm text-gray-700">{{ $admin->name }}</span>
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center">
                        {{ strtoupper(substr($admin->name, 0, 2)) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto px-6 py-6">
            @yield('content')
        </main>

    </div>

</body>
</html>