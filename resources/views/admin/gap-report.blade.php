@extends('layouts.admin')
@section('title', 'Gap Report')

@section('content')

{{-- Header with Export Button --}}
<div class="flex items-start justify-between mb-6 gap-4">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 flex-1">
        <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
            <p class="text-sm text-gray-500 mb-1">Total searches</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalSearches }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
            <p class="text-sm text-gray-500 mb-1">Unmatched searches</p>
            <p class="text-3xl font-bold text-su-red">{{ $totalUnmatched }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
            <p class="text-sm text-gray-500 mb-1">Match rate</p>
            <p class="text-3xl font-bold text-su-blue">
                {{ $totalSearches > 0 ? round((($totalSearches - $totalUnmatched) / $totalSearches) * 100) : 100 }}%
            </p>
        </div>
    </div>
    <button onclick="exportPDF()"
        style="background-color: #2D3092; color: white; border: none; cursor: pointer;"
        class="flex-shrink-0 flex items-center gap-2 text-sm font-semibold px-5 py-3 rounded-xl transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Export PDF
    </button>
</div>

{{-- Unmatched unit codes --}}
@if($filterGaps->isNotEmpty())
<div class="bg-white border border-gray-200 rounded-xl px-6 py-5 mb-6">
    <h2 class="text-sm font-semibold text-gray-700 mb-4">Most searched-for unit codes with no results</h2>
    <div class="space-y-3">
        @php $maxFilter = $filterGaps->max(); @endphp
        @foreach($filterGaps as $unitCode => $count)
            <div class="flex items-center gap-3 text-sm">
                <span class="text-gray-600 w-32 truncate font-medium">{{ $unitCode }}</span>
                <div class="flex-1 h-1.5 bg-gray-100 rounded-full">
                    <div class="h-1.5 bg-su-red rounded-full"
                        style="width: {{ round($count / $maxFilter * 100) }}%">
                    </div>
                </div>
                <span class="text-su-red font-semibold w-8 text-right">{{ $count }}</span>
                <span class="bg-su-gold-light text-su-gold text-xs font-medium px-2 py-0.5 rounded-full">
                    Missing
                </span>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- All unmatched search terms --}}
<div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
    <h2 class="text-sm font-semibold text-gray-700 mb-4">Unmatched keyword searches</h2>

    @if($gapReport->isEmpty())
        <p class="text-sm text-gray-400 text-center py-6">No unmatched searches recorded.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[500px]">
                <thead>
                    <tr class="text-xs text-gray-400 border-b border-gray-100">
                        <th class="text-left pb-2 font-medium">Search query</th>
                        <th class="text-left pb-2 font-medium">Times searched</th>
                        <th class="text-left pb-2 font-medium">Last searched</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gapReport as $gap)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="py-3 text-gray-700">{{ $gap->query_string }}</td>
                            <td class="py-3 text-su-red font-semibold">{{ $gap->total }}</td>
                            <td class="py-3 text-gray-400 text-xs">
                                {{ \Carbon\Carbon::parse($gap->last_searched)->diffForHumans() }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $gapReport->links() }}
        </div>
    @endif
</div>

{{-- Hidden printable PDF version --}}
<div id="pdf-content" class="hidden">
    <div style="font-family: Arial, sans-serif; padding: 30px;">

        {{-- PDF Header --}}
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px; border-bottom: 2px solid #2D3092; padding-bottom: 16px;">
            <img src="{{ asset('images/logo.png') }}" style="height: 50px; width: auto;" alt="Strathmore University">
            <div>
                <h1 style="margin: 0; font-size: 18px; color: #2D3092;">Strathmore University Flashcard Platform</h1>
                <p style="margin: 4px 0 0; font-size: 12px; color: #666;">
                    Gap Report — Generated on {{ now()->format('d M Y, H:i') }}
                </p>
            </div>
        </div>

        {{-- PDF Stats --}}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; text-align: center;">
                <p style="margin: 0; font-size: 11px; color: #6b7280;">Total Searches</p>
                <p style="margin: 4px 0 0; font-size: 24px; font-weight: bold; color: #111827;">
                    {{ $totalSearches }}
                </p>
            </div>
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; text-align: center;">
                <p style="margin: 0; font-size: 11px; color: #6b7280;">Unmatched Searches</p>
                <p style="margin: 4px 0 0; font-size: 24px; font-weight: bold; color: #E8402A;">
                    {{ $totalUnmatched }}
                </p>
            </div>
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; text-align: center;">
                <p style="margin: 0; font-size: 11px; color: #6b7280;">Match Rate</p>
                <p style="margin: 4px 0 0; font-size: 24px; font-weight: bold; color: #2D3092;">
                    {{ $totalSearches > 0 ? round((($totalSearches - $totalUnmatched) / $totalSearches) * 100) : 100 }}%
                </p>
            </div>
        </div>

        {{-- PDF Unit Codes Table --}}
        @if($filterGaps->isNotEmpty())
        <h2 style="font-size: 14px; color: #2D3092; margin-bottom: 12px; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px;">
            Most Searched Unit Codes With No Results
        </h2>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px; font-size: 12px;">
            <thead>
                <tr style="background: #f9fafb;">
                    <th style="text-align: left; padding: 8px 12px; border: 1px solid #e5e7eb; color: #374151;">
                        Unit Code
                    </th>
                    <th style="text-align: left; padding: 8px 12px; border: 1px solid #e5e7eb; color: #374151;">
                        Search Count
                    </th>
                    <th style="text-align: left; padding: 8px 12px; border: 1px solid #e5e7eb; color: #374151;">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($filterGaps as $unitCode => $count)
                <tr>
                    <td style="padding: 8px 12px; border: 1px solid #e5e7eb;">{{ $unitCode }}</td>
                    <td style="padding: 8px 12px; border: 1px solid #e5e7eb; color: #E8402A; font-weight: bold;">
                        {{ $count }}
                    </td>
                    <td style="padding: 8px 12px; border: 1px solid #e5e7eb;">
                        <span style="background: #FBF3DC; color: #D4A017; padding: 2px 8px; border-radius: 12px; font-size: 11px;">
                            Missing
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- PDF Keyword Table --}}
        @if($gapReport->isNotEmpty())
        <h2 style="font-size: 14px; color: #2D3092; margin-bottom: 12px; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px;">
            Unmatched Keyword Searches
        </h2>
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr style="background: #f9fafb;">
                    <th style="text-align: left; padding: 8px 12px; border: 1px solid #e5e7eb; color: #374151;">
                        Search Query
                    </th>
                    <th style="text-align: left; padding: 8px 12px; border: 1px solid #e5e7eb; color: #374151;">
                        Times Searched
                    </th>
                    <th style="text-align: left; padding: 8px 12px; border: 1px solid #e5e7eb; color: #374151;">
                        Last Searched
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($gapReport as $gap)
                <tr>
                    <td style="padding: 8px 12px; border: 1px solid #e5e7eb;">
                        {{ $gap->query_string }}
                    </td>
                    <td style="padding: 8px 12px; border: 1px solid #e5e7eb; color: #E8402A; font-weight: bold;">
                        {{ $gap->total }}
                    </td>
                    <td style="padding: 8px 12px; border: 1px solid #e5e7eb; color: #6b7280;">
                        {{ \Carbon\Carbon::parse($gap->last_searched)->format('d M Y, H:i') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- PDF Footer --}}
        <p style="margin-top: 24px; font-size: 10px; color: #9ca3af; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 12px;">
            Strathmore University Flashcard Platform — Confidential Gap Report
        </p>

    </div>
</div>

<script>
    function exportPDF() {
        const pdfContent = document.getElementById('pdf-content').innerHTML;
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Gap Report — Strathmore Flashcards</title>
                <style>
                    @page { margin: 20mm; }
                    body { margin: 0; padding: 0; }
                    * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                </style>
            </head>
            <body>${pdfContent}</body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    }
</script>

@endsection