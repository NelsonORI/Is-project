<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\FlaskOcrService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FlaskOcrServiceTest extends TestCase
{
    public function test_returns_error_when_flask_is_unreachable()
    {
        Http::fake([
            'localhost:5000/*' => function () {
                throw new \Exception('Connection refused');
            }
        ]);

        Storage::fake('local');
        Storage::put('uploads/papers/test.pdf', 'fake pdf content');

        $service = new FlaskOcrService();
        $result  = $service->processDocument('uploads/papers/test.pdf', [
            'unit_code'     => 'BBT 2202',
            'unit_name'     => 'Advanced OOP',
            'lecturer'      => 'Dr. Omondi',
            'academic_year' => '2022/2023',
            'semester'      => 'Semester 2',
            'exam_type'     => 'Final',
        ]);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Could not reach the OCR service', $result['error']);
    }

    public function test_returns_error_when_flask_returns_500()
    {
        Http::fake([
            'localhost:5000/*' => Http::response(['error' => 'OCR failed'], 500),
        ]);

        Storage::fake('local');
        Storage::put('uploads/papers/test.pdf', 'fake pdf content');

        $service = new FlaskOcrService();
        $result  = $service->processDocument('uploads/papers/test.pdf', [
            'unit_code'     => 'BBT 2202',
            'unit_name'     => 'Advanced OOP',
            'lecturer'      => 'Dr. Omondi',
            'academic_year' => '2022/2023',
            'semester'      => 'Semester 2',
            'exam_type'     => 'Final',
        ]);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('OCR service returned an error', $result['error']);
    }

    public function test_returns_flashcards_on_successful_response()
    {
        Http::fake([
            'localhost:5000/*' => Http::response([
                'success'    => true,
                'flashcards' => [
                    ['question' => 'What is OOP?', 'answer' => 'Object Oriented Programming', 'confidence_score' => 0.95],
                ],
                'page_count' => 2,
                'confidence' => 0.95,
            ], 200),
        ]);

        Storage::fake('local');
        Storage::put('uploads/papers/test.pdf', 'fake pdf content');

        $service = new FlaskOcrService();
        $result  = $service->processDocument('uploads/papers/test.pdf', [
            'unit_code'     => 'BBT 2202',
            'unit_name'     => 'Advanced OOP',
            'lecturer'      => 'Dr. Omondi',
            'academic_year' => '2022/2023',
            'semester'      => 'Semester 2',
            'exam_type'     => 'Final',
        ]);

        $this->assertTrue($result['success']);
        $this->assertCount(1, $result['flashcards']);
        $this->assertEquals('What is OOP?', $result['flashcards'][0]['question']);
    }
}