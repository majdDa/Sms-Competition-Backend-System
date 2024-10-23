<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingHistorySy extends Model
{
    use HasFactory;


    protected $table = 'pending_history_sy';

    protected $fillable = [
        'gsm',
        'command',
        'response',
        'status',
        'attempt_number',
        'attemp_date'
    ];

    protected $casts = [
        'attempt_date' => 'datetime',

    ];
}
