<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ClassRep extends Model
{
    use HasUuids;

    protected $table = 'class_reps';

    protected $fillable = [
        'student_id',
        'approved_by',
        'class_name',
        'approved',
        'approved_at',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function approvedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'class_rep_id');
    }
}