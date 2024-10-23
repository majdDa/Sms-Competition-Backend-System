<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PendingGsmSy;

class PendingSmsSy extends Model
{
    use HasFactory;
    protected $table = 'pending_sms_sy';

    protected $fillable = [
        'pending_id',
        'request_id',
        'sms',
        'is_processed',
        'command',
        'mt',
        'op_response',
        'short_code',

    ];

    public function pending_SyGsm() //HasOne
    {
        return $this->belongsTo(PendingGsmSy::class, 'pending_id', 'id');
    }
}
