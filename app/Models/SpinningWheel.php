<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Relations\HasOne;


class SpinningWheel extends Model
{
    use HasFactory;
    protected $fillable = ['gsm', 'subscriber_id', 'points', 'verification_code', 'status', 'counter'];



    public function subscriber(): HasOne
    {
        return $this->hasOne(Subscriber::class, 'id', 'subscriber_id');
    }
}
