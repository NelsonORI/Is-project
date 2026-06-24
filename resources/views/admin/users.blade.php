@extends('layouts.admin')
@section('title', 'Manage Users')

@section('content')

@if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

{{-- Filters --}}
<div class="bg-white border border-gray-200 rounded-xl px-6 py-4 mb-6">
    <form method="GET" action="{{ route('admin.users') }}" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Search by name, email or student number..."
            class="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">

        <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="">All statuses</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
        </select>

        <select name="role" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="">All roles</option>
            <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
            <option value="class_rep" {{ request('role') === 'class_rep' ? 'selected' : '' }}>Class Rep</option>
        </select>

        <button type="submit"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2 rounded-lg transition">
            Filter
        </button>
        <a href="{{ route('admin.users') }}"
            class="border border-gray-300 text-gray-600 text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-50 transition">
            Clear
        </a>
    </form>
</div>

{{-- Users Table --}}
<div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-xs text-gray-400 border-b border-gray-100">
                <th class="text-left pb-2 font-medium">Name</th>
                <th class="text-left pb-2 font-medium">Email</th>
                <th class="text-left pb-2 font-medium">Programme</th>
                <th class="text-left pb-2 font-medium">Role</th>
                <th class="text-left pb-2 font-medium">Status</th>
                <th class="text-right pb-2 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="border-b border-gray-50">
                    <td class="py-3 text-gray-700">{{ $user->name }}</td>
                    <td class="py-3 text-gray-500">{{ $user->email }}</td>
                    <td class="py-3 text-gray-500">{{ $user->programme }} · Yr {{ $user->year_of_study }}</td>
                    <td class="py-3">
                        @if($user->role === 'class_rep')
                            <span class="bg-indigo-100 text-indigo-700 text-xs font-medium px-2 py-0.5 rounded-full">Class Rep</span>
                        @else
                            <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-0.5 rounded-full">Student</span>
                        @endif
                    </td>
                    <td class="py-3">
                        @if($user->status === 'active')
                            <span class="bg-green-100 text-green-700 text-xs font-medium px-2 py-0.5 rounded-full">Active</span>
                        @elseif($user->status === 'pending')
                            <span class="bg-amber-100 text-amber-700 text-xs font-medium px-2 py-0.5 rounded-full">Pending</span>
                        @else
                            <span class="bg-red-100 text-red-700 text-xs font-medium px-2 py-0.5 rounded-full">Suspended</span>
                        @endif
                    </td>
                    <td class="py-3 text-right">
                        <div class="flex gap-1.5 justify-end">

                            @if($user->status === 'suspended')
                                <form method="POST" action="{{ route('admin.users.activate', $user->id) }}">
                                    @csrf
                                    <button type="submit" class="text-xs text-green-700 border border-green-200 hover:bg-green-50 rounded px-2 py-1">
                                        Reactivate
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.users.suspend', $user->id) }}"
                                    onsubmit="return confirm('Suspend this account?');">
                                    @csrf
                                    <button type="submit" class="text-xs text-red-600 border border-red-200 hover:bg-red-50 rounded px-2 py-1">
                                        Suspend
                                    </button>
                                </form>
                            @endif

                            @if($user->role === 'class_rep')
                                <form method="POST" action="{{ route('admin.users.revoke-classrep', $user->id) }}"
                                    onsubmit="return confirm('Revoke class rep status?');">
                                    @csrf
                                    <button type="submit" class="text-xs text-gray-600 border border-gray-200 hover:bg-gray-50 rounded px-2 py-1">
                                        Revoke Rep
                                    </button>
                                </form>
                            @endif

                        </div>
                    </td>
                    <td class="py-3 text-right">
                        <div class="flex gap-1.5 justify-end">

                            @if($user->status === 'suspended')
                                <form method="POST" action="{{ route('admin.users.activate', $user->id) }}">
                                    @csrf
                                    <button type="submit" class="text-xs text-green-700 border border-green-200 hover:bg-green-50 rounded px-2 py-1">
                                        Reactivate
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.users.suspend', $user->id) }}"
                                    onsubmit="return confirm('Suspend this account?');">
                                    @csrf
                                    <button type="submit" class="text-xs text-red-600 border border-red-200 hover:bg-red-50 rounded px-2 py-1">
                                        Suspend
                                    </button>
                                </form>
                            @endif

                            @if($user->role === 'class_rep')
                                <form method="POST" action="{{ route('admin.users.revoke-classrep', $user->id) }}"
                                    onsubmit="return confirm('Revoke class rep status? Their uploaded content will remain on the platform.');">
                                    @csrf
                                    <button type="submit" class="text-xs text-gray-600 border border-gray-200 hover:bg-gray-50 rounded px-2 py-1">
                                        Revoke Rep
                                    </button>
                                </form>
                            @else
                                <button type="button"
                                    onclick="document.getElementById('promote-modal-{{ $user->id }}').classList.remove('hidden')"
                                    class="text-xs text-indigo-600 border border-indigo-200 hover:bg-indigo-50 rounded px-2 py-1">
                                    Make Class Rep
                                </button>
                            @endif

                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>

{{-- Promote to Class Rep Modals --}}
@foreach($users as $user)
    @if($user->role !== 'class_rep')
        <div id="promote-modal-{{ $user->id }}"
            class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-6 w-full max-w-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Promote to Class Rep</h3>
                <p class="text-sm text-gray-400 mb-4">
                    Grant <span class="font-medium text-gray-600">{{ $user->name }}</span> upload privileges.
                </p>

                <form method="POST" action="{{ route('admin.users.promote-classrep', $user->id) }}">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700 mb-1">Class Name</label>
                    <input type="text" name="class_name" required
                        placeholder="e.g. BBIT Year 3"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm mb-4 focus:outline-none focus:ring-2 focus:ring-indigo-400">

                    <div class="flex gap-3">
                        <button type="button"
                            onclick="document.getElementById('promote-modal-{{ $user->id }}').classList.add('hidden')"
                            class="flex-1 border border-gray-300 text-gray-600 font-medium py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg text-sm transition">
                            Promote
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endforeach

@endsection
