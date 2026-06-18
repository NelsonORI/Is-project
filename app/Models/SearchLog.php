<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SearchLog extends Model
{
    use HasUuids;

    protected $table = 'search_logs';

    protected $fillable = [
        'student_id',
        'query_string',
        'filter_params',
        'exact_match_found',
        'searched_at',
    ];

    protected $casts = [
        'filter_params' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}