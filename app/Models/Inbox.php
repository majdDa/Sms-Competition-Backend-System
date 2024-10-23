<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Keyword;
use App\Models\Question;
use App\Models\Subscriber;
use App\Models\PendingHistorySy;
use App\Models\PendingHistoryMTN;



class Inbox extends Model
{
    use HasFactory;

    protected $table = 'inbox';


    protected $fillable = [
        'gsm',
        'subscriber_id',
        'gsm',
        'operator',
        'sms',
        'status',
        'short_code',
        'question_id',
        'keyword_id',
        'sms_mt',
        'points',
        'request_id',
        'pending_id_mtn',
        'pending_id_sy',
        'type',
        'op_timestamp',
        'lang_id',
    ];


    protected $casts = [
        'type' => 'array'
    ];


    public function keyword()  //HasOne
    {
        return $this->belongsTo(Keyword::class);
    }


    public function question()  //HasOne
    {
        return $this->belongsTo(Question::class);
    }

    public function subscriber()  //HasOne
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function pending_mtn()  //HasOne
    {
        return $this->belongsTo(PendingHistoryMTN::class);
    }


    public function pending_sy()  //HasOne
    {
        return $this->belongsTo(PendingHistorySy::class);
    }
}