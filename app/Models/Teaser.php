<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teaser extends Model
{
    use HasFactory;
    protected $fillable=[
        'mtxt',
        'sending_date',
        'ctg',
        'status_mtn',
        'status_syriatel',
        'ip',
        'op_id',
    ];

}
