<?php

namespace App\Interfaces\Application;

interface ISendReportRepository
{
    public function get_chart_data($request);
    public function get_chart_data_v2($request);
    public function send_report();


    // --------
    public function new_get_chart_data_v2($request);
}
