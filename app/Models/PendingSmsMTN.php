<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PendingGsmMTN;

class PendingSmsMTN extends Model
{
    use HasFactory;

    protected $table = 'pending_sms_mtn';

    protected $fillable = [
        'pending_id',
        'short_code',
        'is_processed',
        'sms',
        'command',
        'mt',
        'op_response',
        'lang_id',
        'op_timestamp'
    ];


    public function pending_gsm()
    {
        return $this->belongsTo(PendingGsmMTN::class, 'pending_id', 'id');
    }
}
