<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchLog;
use Illuminate\Support\Facades\DB;

class AdminGapReportController extends Controller
{
    public function index()
    {
        // Group unmatched searches by query string
        $gapReport = SearchLog::where('exact_match_found', false)
            ->select('query_string', DB::raw('count(*) as total'), DB::raw('MAX(searched_at) as last_searched'))
            ->whereNotNull('query_string')
            ->where('query_string', '!=', '')
            ->groupBy('query_string')
            ->orderByDesc('total')
            ->paginate(20);

        // Group unmatched searches by filter params (unit code)
        $filterGaps = SearchLog::where('exact_match_found', false)
            ->whereNotNull('filter_params')
            ->get()
            ->groupBy(function ($log) {
                $filters = $log->filter_params;
                return $filters['unit_code'] ?? null;
            })
            ->filter(fn($group, $key) => $key !== null && $key !== '')
            ->map(fn($group) => $group->count())
            ->sortDesc()
            ->take(10);

        $totalUnmatched = SearchLog::where('exact_match_found', false)->count();
        $totalSearches  = SearchLog::count();

        return view('admin.gap-report', compact('gapReport', 'filterGaps', 'totalUnmatched', 'totalSearches'));
    }
}