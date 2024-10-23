<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Interfaces\Domain\IPendingMTNRepository;
use App\Interfaces\Application\IMTNRepository;



class ProcessRenewalRequestJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $renewalRequests;
    private $pendingMTNRepository;
    private $MTNRepository;

    /**
     * Create a new job instance.
     *
     * @param  array  $renewalRequests
     * @return void
     */
    public function __construct(array $renewalRequests, IPendingMTNRepository $pendingMTNRepository, IMTNRepository $MTNRepository)
    {
        $this->renewalRequests = $renewalRequests;
        $this->pendingMTNRepository = $pendingMTNRepository;
        $this->MTNRepository = $MTNRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->renewalRequests as $renewalRequest) {
            $ticketId = $this->MTNRepository->call_renewal_api($renewalRequest->gsm);
            $this->pendingMTNRepository->update_is_renewaled($renewalRequest->id, $ticketId);
        }
    }
}
