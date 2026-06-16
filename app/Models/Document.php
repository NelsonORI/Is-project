<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Document extends Model
{
    use HasUuids;

    protected $table = 'documents';

    protected $fillable = [
        'class_rep_id',
        'original_filename',
        'storage_path',
        'processing_status',
        'uploaded_at',
    ];

    public function classRep()
    {
        return $this->belongsTo(ClassRep::class, 'class_rep_id');
    }

    public function flashcards()
    {
        return $this->hasMany(Flashcard::class, 'document_id');
    }
}