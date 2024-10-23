<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingGsmMTN extends Model
{
    use HasFactory;

    protected $table = 'pending_gsm_mtn';

    protected $fillable = [
        'gsm',
        'command',
        'response',
        'status',
        'attempt',
        'attempt_date',
        'renewal_by',
        'mt',
        'sc',
        'is_processed',
        'cancel_balance_mt'

    ];


    protected $casts = [

        'attempt_date' => 'datetime',

    ];
    public function PendingSmsMTN()
    {
        return $this->hasMany(PendingSmsMTN::class, 'pending_id', 'id');
    }
}