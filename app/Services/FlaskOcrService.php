<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlaskOcrService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.flask.url', 'http://localhost:5000');
    }

    public function processDocument(string $storagePath, array $metadata): array
    {
        try {
            $response = Http::timeout(120)
                ->attach(
                    'pdf',
                    file_get_contents(storage_path('app/' . $storagePath)),
                    basename($storagePath)
                )
                ->post("{$this->baseUrl}/process", [
                    'unit_code'     => $metadata['unit_code'],
                    'unit_name'     => $metadata['unit_name'],
                    'lecturer'      => $metadata['lecturer'],
                    'academic_year' => $metadata['academic_year'],
                    'semester'      => $metadata['semester'],
                    'exam_type'     => $metadata['exam_type'],
                ]);

            if ($response->successful()) {
                return [
                    'success'    => true,
                    'flashcards' => $response->json('flashcards'),
                ];
            }

            Log::error('Flask OCR service error', [
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);

            return [
                'success' => false,
                'error'   => 'OCR service returned an error. Please try again.',
            ];

        } catch (\Exception $e) {
            Log::error('Flask OCR service exception', ['message' => $e->getMessage()]);

            return [
                'success' => false,
                'error'   => 'Could not reach the OCR service. Please try again later.',
            ];
        }
    }
}