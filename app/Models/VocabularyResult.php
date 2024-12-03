<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocabularyResult extends Model
{
    use HasFactory;
    protected $table = "vocabulary_results";
    protected $fillable = [
        'user_id',
        'percent',
        'correct',
        'incorrect',
        'total',
    ];
}
