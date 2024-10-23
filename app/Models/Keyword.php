<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;

    protected $table = 'key_words';


    protected $fillable = [
        'name',
        'is_active',
        'type',
        'points'
    ];
}
