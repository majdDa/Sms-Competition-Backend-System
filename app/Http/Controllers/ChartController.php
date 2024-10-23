<?php

namespace App\Http\Controllers;

use App\Interfaces\Application\ISendReportRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChartController extends Controller
{
    protected $ISendReportRepository;
    public function __construct(ISendReportRepository $ISendReportRepository)
    {
        $this->ISendReportRepository = $ISendReportRepository;
    }

    public function report_mtn(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'date' => 'required |before_or_equal:today '
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }


        return $this->ISendReportRepository->get_chart_data($request);
    }


    public function report_mtn_v2(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'date' => [
                'required',
                'date_format:Y-m'
            ],
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }
        return $this->ISendReportRepository->get_chart_data_v2($request);
    }

    public function new_report_mtn_v2(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'date' => [
                'required',
                'date_format:Y-m'
            ],
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }
        return $this->ISendReportRepository->new_get_chart_data_v2($request);
    }

    public function sendReport()
    {
        return $this->ISendReportRepository->send_report();
    }
}
