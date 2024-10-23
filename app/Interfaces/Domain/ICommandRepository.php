<?php

namespace App\Interfaces\Domain;

interface ICommandRepository
{
    public function getQustionCommandsByName(string $name): object;
    public function getBalanceCommandsByName(string $name): object;
    public function getHelpCommandsByName(string $name): object;
    public function getDeactivationCommandsByName(string $name);
    public function isCommandExist(string $name): bool;
    public function getCommandByName(string $name): object;
}
