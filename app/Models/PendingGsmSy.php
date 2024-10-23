<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PendingSmsSy;

class PendingGsmSy extends Model
{
    use HasFactory;

    protected $table = 'pending_gsm_sy';

    protected $fillable = [
        'gsm',
        'command',
        'response',
        'status',
        'attempt_number',
        'attempt_date'
    ];

    protected $casts = [
        'attempt_date' => 'datetime',
    ];

    public function PendingSmsSy()
    {
        return $this->hasMany(PendingSmsSy::class, 'pending_id', 'id');
    }
}
