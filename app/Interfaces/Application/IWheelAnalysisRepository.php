<?php

namespace App\Interfaces\Application;

interface IWheelAnalysisRepository
{
    public function sendVerificationCode(string $gsm): int;
    public function login(string $gsm, string $code): int;
    public function spin(string $gsm, int $points, int $counter = 0): bool;
}
