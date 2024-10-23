<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyRenewalSy extends Model
{
    use HasFactory;

    protected $table = 'daily_renewal_sy';


    protected $fillable = ['gsm', 'status', 'subscriber_id'];
}
