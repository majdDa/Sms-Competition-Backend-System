<?php

namespace App\Interfaces\Application;

interface IActivationMTNRepository
{
    public function check_response_status($request);
    public function activation($request);
    public function deactivation($request);
    public function others($request);

    public function get_gsms_to_canceled();
}
