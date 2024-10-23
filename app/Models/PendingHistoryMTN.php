<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingHistoryMTN extends Model
{
    use HasFactory;

    protected $table = 'pending_history_mtn';

    protected $fillable = [
        'gsm',
        'command',
        'response',
        'status',
        'attempt',
        'attempt_date',
        'renewal_by',
        'mt',
        'op_response',
        'cancel_balance_mt'
    ];


    protected $casts = [
        'attempt_date' => 'datetime',
    ];
}
