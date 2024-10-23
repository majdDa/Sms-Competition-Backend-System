<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $table = 'subscribers';

    protected $fillable = [
        'id',
        'gsm',
        'operator',
        'sub_date',
        'cancel_date',
        'last_response_date',
        'last_answer',
        'question_order',
        'short_code',
        'score',
        'sub_status'
    ];


    protected $casts = [

        'sub_date' => 'datetime',
        'last_response_date' => 'datetime',
        'cancel_date' => 'datetime'

    ];


    public function inbox()
    {
        return $this->hasMany(Inbox::class);
    }
}
