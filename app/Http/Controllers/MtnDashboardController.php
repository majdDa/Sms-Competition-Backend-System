<?php

namespace App\Http\Controllers;

use App\Interfaces\Application\IMtnDashboardRepository;

use Illuminate\Http\Request;

class MtnDashboardController extends Controller
{
    protected $IMtnDashboardRepository;

    public function __construct(IMtnDashboardRepository $IMtnDashboardRepository)
    {
        $this->IMtnDashboardRepository = $IMtnDashboardRepository;
    }

    public function get_all_mtn_users(Request $filter)
    {
        return $this->IMtnDashboardRepository->get_all_mtn_users($filter);
    }

    public function deactivate(Request $gsm)
    {
        return $this->IMtnDashboardRepository->deactivate($gsm);
    }

    public function search(Request $request)
    {
        return $this->IMtnDashboardRepository->search($request);
    }

    public function get_gsm_messages(Request $gsm)
    {
        return $this->IMtnDashboardRepository->get_gsm_messages($gsm);
    }


    // ================ 

    public function new_get_all_mtn_users(Request $filter)
    {
        return $this->IMtnDashboardRepository->new_get_all_mtn_users($filter);
    }

    public function new_deactivate(Request $gsm)
    {
        return $this->IMtnDashboardRepository->new_deactivate($gsm);
    }



    public function new_get_gsm_messages(Request $gsm)
    {
        return $this->IMtnDashboardRepository->new_get_gsm_messages($gsm);
    }
}
