<?php

namespace App\Repositories\Application;

use App\Interfaces\Application\IWheelAnalysisRepository;
use App\Interfaces\Domain\ISpinningWheelRepository;
use App\Interfaces\Domain\ISubscribersRepository;
use App\Models\SpinningWheel;

class WheelAnalysisRepository implements IWheelAnalysisRepository
{
    private $_WheelRepository;
    private $_subscriberRepository;

    public function __construct(ISpinningWheelRepository $wheelRepository, ISubscribersRepository $subscriberRepository)
    {
        $this->_WheelRepository = $wheelRepository;
        $this->_subscriberRepository = $subscriberRepository;
    }


    public function sendVerificationCode(string $gsm): int
    {
        $isSubscriber = $this->_WheelRepository->checkIsSubscriber($gsm);
        $spinToday = $this->_WheelRepository->checkSpinToday($gsm);
        if (!$isSubscriber) {
            return -1;
        } else  if ($spinToday) {
            return 0;
        } else {
            $this->_WheelRepository->createPlayer($gsm);
            $this->_WheelRepository->sendCode($gsm);
            return 1;
            //return $this->_WheelRepository->sendCode($gsm);
        }
    }

    public function login(string $gsm, string $code): int
    {
        $verifyCode = $this->_WheelRepository->verifyCode($gsm, $code);
        return $verifyCode;
    }

    public function spin(string $gsm, int $points, int $counter = 0): bool
    {
        $spinToday = $this->_WheelRepository->checkSpinToday($gsm);
        $isSubscriber = $this->_WheelRepository->checkIsSubscriber($gsm);
        if ($spinToday || !$isSubscriber) {
            return 0;
        } else {
            $this->_WheelRepository->spin($gsm, $points, $counter);
            return 1;
        }
    }
}
