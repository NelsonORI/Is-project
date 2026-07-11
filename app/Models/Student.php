<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasUuids, HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'name',
        'email',
        'password_hash',
        'role',
        'student_number',
        'school',
        'programme',
        'year_of_study',
        'status',
    ];

    protected $hidden = [
        'password_hash',
    ];

    // Tell Laravel to use password_hash instead of password
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Tell Laravel the name of the password field
    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }
    
    // Relationships
    public function classRep()
    {
        return $this->hasOne(ClassRep::class, 'student_id');
    }

    public function searchLogs()
    {
        return $this->hasMany(SearchLog::class, 'student_id');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class, 'student_id');
    }

    // Role helpers
    public function isClassRep(): bool
    {
        return $this->role === 'class_rep';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}