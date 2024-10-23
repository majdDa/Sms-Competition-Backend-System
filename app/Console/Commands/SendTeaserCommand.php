<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TeaserController;
use Illuminate\Container\Container;

class SendTeaserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teaser:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $container = Container::getInstance();
        $teaserController = $container->make(TeaserController::class);
        $teaserController->send();
    }
}