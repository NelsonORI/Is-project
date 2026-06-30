@extends('layouts.admin')
@section('title', 'Admin Dashboard')

@section('content')

{{-- Flash message --}}
@if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

{{-- Stats Row --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
        <p class="text-sm text-gray-500 mb-1">Total students</p>
        <p class="text-3xl font-bold text-su-blue">{{ $totalStudents }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
        <p class="text-sm text-gray-500 mb-1">Class reps</p>
        <p class="text-3xl font-bold text-su-gold">{{ $totalClassReps }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
        <p class="text-sm text-gray-500 mb-1">Documents uploaded</p>
        <p class="text-3xl font-bold text-amber-600">{{ $totalDocuments }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
        <p class="text-sm text-gray-500 mb-1">Unmatched searches</p>
        <p class="text-3xl font-bold text-su-red">{{ $unmatchedSearches }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

    {{-- Recent Users --}}
    <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-700">Recent users</h2>
            <a href="{{ route('admin.users') }}" class="text-xs text-su-blue hover:underline">View all →</a>
        </div>

        @if($recentUsers->isEmpty())
            <p class="text-sm text-gray-400 text-center py-6">No users yet.</p>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 border-b border-gray-100">
                        <th class="text-left pb-2 font-medium">Name</th>
                        <th class="text-left pb-2 font-medium">Programme</th>
                        <th class="text-left pb-2 font-medium">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $user)
                        <tr class="border-b border-gray-50">
                            <td class="py-2.5 text-gray-700">{{ $user->name }}</td>
                            <td class="py-2.5 text-gray-500">{{ $user->programme }}</td>
                            <td class="py-2.5">
                                @if($user->status === 'active')
                                    <span class="bg-green-100 text-green-700 text-xs font-medium px-2 py-0.5 rounded-full">Active</span>
                                @elseif($user->status === 'pending')
                                    <span class="bg-amber-100 text-amber-700 text-xs font-medium px-2 py-0.5 rounded-full">Pending</span>
                                @else
                                    <span class="bg-red-100 text-red-700 text-xs font-medium px-2 py-0.5 rounded-full">Suspended</span>
                                @endif
                            </td>
                            <td class="py-2.5 text-right">
                                <a href="{{ route('admin.users') }}" class="text-xs text-gray-500 hover:text-indigo-600 border border-gray-200 rounded px-2 py-1">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Class Rep Approvals --}}
    <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-700">Class rep approvals</h2>
            <a href="{{ route('admin.classreps') }}" class="text-xs text-indigo-600 hover:underline">View all →</a>
        </div>

        @if($pendingApprovals->isEmpty())
            <p class="text-sm text-gray-400 text-center py-6">No pending requests.</p>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 border-b border-gray-100">
                        <th class="text-left pb-2 font-medium">Student</th>
                        <th class="text-left pb-2 font-medium">Class</th>
                        <th class="text-left pb-2 font-medium">Requested</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingApprovals as $req)
                        <tr class="border-b border-gray-50">
                            <td class="py-2.5 text-gray-700">{{ $req->student->name }}</td>
                            <td class="py-2.5 text-gray-500">{{ $req->class_name }}</td>
                            <td class="py-2.5 text-gray-400 text-xs">{{ $req->created_at->diffForHumans() }}</td>
                            <td class="py-2.5 text-right">
                                <div class="flex gap-1 justify-end">
                                    <form method="POST" action="{{ route('admin.classreps.approve', $req->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="text-xs text-green-700 border border-green-200 hover:bg-green-50 rounded px-2 py-1">
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.classreps.reject', $req->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="text-xs text-red-600 border border-red-200 hover:bg-red-50 rounded px-2 py-1">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

{{-- Gap Report Preview --}}
<div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-700">Gap report — top unmatched searches</h2>
        <a href="{{ route('admin.gap-report') }}" class="text-xs text-indigo-600 hover:underline">View full report →</a>
    </div>

    @if($gapReport->isEmpty())
        <p class="text-sm text-gray-400 text-center py-6">No unmatched searches yet.</p>
    @else
        <div class="space-y-3">
            @foreach($gapReport as $gap)
                <div class="flex items-center gap-3 text-sm">
                    <span class="text-gray-600 w-32 truncate">{{ $gap->query_string }}</span>
                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full">
                        <div class="h-1.5 bg-su-red rounded-full" style="width: {{ round($gap->total / $maxGapCount * 100) }}%"></div>
                    </div>
                    <span class="text-red-600 font-semibold w-8 text-right">{{ $gap->total }}</span>
                    <span class="bg-su-gold-light text-su-gold text-xs font-medium px-2 py-0.5 rounded-full">Missing</span>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection