<?php

namespace App\Interfaces\Application;

interface IActivationSyRepository
{
    public function activation($request);
    public function de_activation($request);
    public function check_response_status($request);
}
