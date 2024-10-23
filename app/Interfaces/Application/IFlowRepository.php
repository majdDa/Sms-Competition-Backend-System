<?php

namespace App\Interfaces\Application;

interface IFlowRepository
{
    public function balanceFlow($inbox, $command_id);
    public function helpFlow($inbox, $command_id);
    public function questionFlow($inbox, $option);
    public function keywordFlow($inbox, $keyword_id);
    public function deactivationFlow($inbox, $command_id);
    public function invalidFlow($inbox);
}
