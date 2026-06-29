<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_accepts_strathmore_email()
    {
        $response = $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test.user@strathmore.edu',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
            'student_number'        => '123456',
            'school'                => 'SCES',
            'programme'             => 'BBIT',
            'year_of_study'         => 2,
        ]);

        $response->assertRedirect('/email/verify');
        $this->assertDatabaseHas('students', ['email' => 'test.user@strathmore.edu']);
    }

    public function test_registration_rejects_gmail_email()
    {
        $response = $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test.user@gmail.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
            'student_number'        => '123457',
            'school'                => 'SCES',
            'programme'             => 'BBIT',
            'year_of_study'         => 2,
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseMissing('students', ['email' => 'test.user@gmail.com']);
    }

    public function test_registration_rejects_non_strathmore_edu_domain()
    {
        $response = $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test.user@strathmore.ac.ke',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
            'student_number'        => '123458',
            'school'                => 'SCES',
            'programme'             => 'BBIT',
            'year_of_study'         => 2,
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseMissing('students', ['email' => 'test.user@strathmore.ac.ke']);
    }
}