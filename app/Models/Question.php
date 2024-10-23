<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = [
        'question_text',
        'order',
        'answer',
        'points',
        'question_date',
        'is_active',
        'created_by',
        'updated_by'
    ];

/* 
    protected $casts = [

        'question_date' => 'date',

    ]; */
}