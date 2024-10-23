<?php

namespace App\Interfaces\Application;

interface IMtnDashboardRepository
{
    public function get_all_mtn_users($filter);
    public function deactivate($gsm);
    public function  search($request);
    public function get_gsm_messages($gsm);

    // --------
    public function new_get_all_mtn_users($request);
    public function new_deactivate($gsm);
    public function new_get_gsm_messages($gsm);
}
