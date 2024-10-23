<?php

namespace App\Interfaces\Application;

interface IReceiveSMS
{
    public function receive_request($request, $op);
    public function check_duplicate_request_id($request_id): bool;
}
