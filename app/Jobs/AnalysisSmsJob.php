<?php

namespace App\Jobs;

use App\Http\Controllers\NintyMinutesCompetitionController;
use App\Interfaces\Application\IAnalysisRepository;
use App\Repositories\Application\AnalysisRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class AnalysisSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private  $_analysisRepository;
    public function __construct(IAnalysisRepository $analysisRepository)
    {
        $this->_analysisRepository = $analysisRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->_analysisRepository->goToFlowBasedOnSms();
    }
}