<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_class_rep_returns_true_for_class_rep_role()
    {
        $student = Student::factory()->create(['role' => 'class_rep']);
        $this->assertTrue($student->isClassRep());
    }

    public function test_is_class_rep_returns_false_for_student_role()
    {
        $student = Student::factory()->create(['role' => 'student']);
        $this->assertFalse($student->isClassRep());
    }

    public function test_is_active_returns_true_for_active_status()
    {
        $student = Student::factory()->create(['status' => 'active']);
        $this->assertTrue($student->isActive());
    }

    public function test_is_active_returns_false_for_suspended_status()
    {
        $student = Student::factory()->create(['status' => 'suspended']);
        $this->assertFalse($student->isActive());
    }
}