<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Flashcard extends Model
{
    use HasUuids;

    protected $table = 'flashcards';

    protected $fillable = [
        'document_id',
        'question',
        'answer',
        'confidence_score',
        'card_order',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function metadata()
    {
        return $this->hasOne(FlashcardMetadata::class, 'flashcard_id');
    }
}