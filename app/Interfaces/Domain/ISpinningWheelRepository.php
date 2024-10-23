<?php

namespace App\Interfaces\Domain;

interface ISpinningWheelRepository
{
    public function checkIsSubscriber(string $gsm): bool;
    public function createPlayer(string $gsm): bool;
    public function checkSpinToday(string $gsm): bool;
    public function verifyCode(string $gsm, string $code): int;
    public function spin(string $gsm, int $points, int $counter = 0): int;
    public function sendCode(string $gsm): int;
}
