<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FlashcardMetadata extends Model
{
    use HasUuids;

    protected $table = 'flashcard_metadata';

    protected $fillable = [
        'flashcard_id',
        'unit_code',
        'lecturer',
        'exam_type',
        'semester',
        'academic_year',
    ];

    public function flashcard()
    {
        return $this->belongsTo(Flashcard::class, 'flashcard_id');
    }
}