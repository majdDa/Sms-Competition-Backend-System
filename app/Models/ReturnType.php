<?php

namespace App\Models;


class ReturnType
{
    public $mt;
    public $op_response;


    public function __construct($op_response, $mt)
    {
        $this->mt = $mt;
        $this->op_response = $op_response;
    }
}
