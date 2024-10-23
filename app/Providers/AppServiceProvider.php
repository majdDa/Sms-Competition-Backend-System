<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\Application\IActivationMTNRepository;
use App\Interfaces\Application\IActivationSyRepository;
use App\Interfaces\Application\IAnalysisRepository;
use App\Interfaces\Application\IFlowRepository;
use App\Interfaces\Application\IMTNRepository;
use App\Interfaces\Application\IReceiveSMS;
use App\Interfaces\Application\ISendMtRepository;
use App\Interfaces\Application\ISyriatelRepository;
use App\Interfaces\Application\IWheelAnalysisRepository;
use App\Interfaces\Application\IAttemptFlowSyriatelRepository;
use App\Interfaces\Application\IAttemptFlowMTNRepository;
use App\Interfaces\Domain\ICommandRepository;
use App\Interfaces\Domain\IDailyRenewalSyRepository;
use App\Interfaces\Domain\IInboxRepository;
use App\Interfaces\Domain\IKeywordsRepository;
use App\Interfaces\Domain\IMessagesRepository;
use App\Interfaces\Domain\IPendingMTNRepository;
use App\Interfaces\Domain\IPendingSyRepository;
use App\Interfaces\Domain\IQuestionsRepository;
use App\Interfaces\Domain\ISpinningWheelRepository;
use App\Interfaces\Domain\ISubscribersRepository;
use App\Services\Interfaces\IOperatorServices;
use App\Interfaces\Domain\ITeasersRepository;
use App\Interfaces\Application\ISendSms;
use App\Interfaces\Application\ISendTeasersRepository;
use App\Interfaces\Application\IMtnDashboardRepository;
use App\Interfaces\Application\ISendReportRepository;



use App\Repositories\Application\ActivationMTNRepository;
use App\Repositories\Application\ActivationSyRepository;
use App\Repositories\Application\AnalysisRepository;
use App\Repositories\Application\FlowRepository;
use App\Repositories\Application\MTNRepository;
use App\Repositories\Application\ReceiveSMS;
use App\Repositories\Application\SendMtRepository;
use App\Repositories\Application\SyriatelRepository;
use App\Repositories\Application\WheelAnalysisRepository;
use App\Repositories\Application\AttemptFlowSyriatelRepository;
use App\Repositories\Application\AttemptFlowMTNRepository;
use App\Repositories\Domain\CommandRepository;
use App\Repositories\Domain\DailyRenewalSyRepository;
use App\Repositories\Domain\InboxRepository;
use App\Repositories\Domain\KeywordsRepository;
use App\Repositories\Domain\MessagesRepository;
use App\Repositories\Domain\PendingMTNRepository;
use App\Repositories\Domain\PendingSyRepository;
use App\Repositories\Domain\QuestionsRepository;
use App\Repositories\Domain\SpinningWheelRepository;
use App\Repositories\Domain\SubscribersRepository;
use App\Services\Factories\OperatorServices;
use App\Repositories\Application\SendSmsRepository;
use App\Repositories\Application\SendTeasersRepository;
use App\Repositories\Domain\TeasersRepository;
use App\Repositories\Application\MtnDashboardRepository;
use App\Repositories\Application\SendReportRepository;




class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IPendingMTNRepository::class, PendingMTNRepository::class);
        $this->app->bind(IPendingSyRepository::class, PendingSyRepository::class);
        $this->app->bind(ISubscribersRepository::class, SubscribersRepository::class);
        $this->app->bind(IInboxRepository::class, InboxRepository::class);
        $this->app->bind(IKeywordsRepository::class, KeywordsRepository::class);
        $this->app->bind(ICommandRepository::class, CommandRepository::class);
        $this->app->bind(IDailyRenewalSyRepository::class, DailyRenewalSyRepository::class);

        $this->app->bind(IAnalysisRepository::class, AnalysisRepository::class);
        $this->app->bind(ISendSms::class, SendSmsRepository::class);

        $this->app->bind(ISpinningWheelRepository::class, SpinningWheelRepository::class);
        $this->app->bind(IWheelAnalysisRepository::class, WheelAnalysisRepository::class);
        $this->app->bind(IFlowRepository::class, FlowRepository::class);
        $this->app->bind(IQuestionsRepository::class, QuestionsRepository::class);
        $this->app->bind(ISendMtRepository::class, SendMtRepository::class);
        $this->app->bind(IMessagesRepository::class, MessagesRepository::class);
        $this->app->bind(ISyriatelRepository::class, SyriatelRepository::class);
        $this->app->bind(IMTNRepository::class, MTNRepository::class);
        $this->app->bind(IReceiveSMS::class, ReceiveSMS::class);
        $this->app->bind(ISendMtRepository::class, SendMtRepository::class);
        $this->app->bind(IOperatorServices::class, OperatorServices::class);
        $this->app->bind(IActivationMTNRepository::class, ActivationMTNRepository::class);
        $this->app->bind(IActivationSyRepository::class, ActivationSyRepository::class);
        $this->app->bind(IAttemptFlowSyriatelRepository::class, AttemptFlowSyriatelRepository::class);
        $this->app->bind(IAttemptFlowMTNRepository::class, AttemptFlowMTNRepository::class);
        $this->app->bind(ITeasersRepository::class, TeasersRepository::class);
        $this->app->bind(ISendTeasersRepository::class, SendTeasersRepository::class);
        $this->app->bind(ISendReportRepository::class, SendReportRepository::class);
        $this->app->bind(IMtnDashboardRepository::class, MtnDashboardRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
