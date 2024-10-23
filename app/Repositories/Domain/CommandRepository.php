<?php

namespace App\Repositories\Domain;

use App\Interfaces\Domain\ICommandRepository;
use App\Models\Command;

class CommandRepository implements ICommandRepository
{
    public function getQustionCommandsByName(string $name): object
    {
        $data = Command::where('name', $name)->where('category', 'question')->first();
        return $data;
    }
    public function getBalanceCommandsByName(string $name): object
    {
        $data = Command::where('name', $name)->where('category', 'balance')->first();
        return $data;
    }
    public function getHelpCommandsByName(string $name): object
    {
        $data = Command::where('name', $name)->where('category', 'help')->first();
        return $data;
    }
    public function getDeactivationCommandsByName(string $name)
    {
        $data = Command::where('name', $name)->where('category', 'deactivation')->first();
        return $data;
    }
    public function isCommandExist(string $name): bool
    {
        $isCommandExist = Command::where('name', $name)->first();
        return !is_null($isCommandExist);
    }
    public function getCommandByName(string $name): object
    {
        if ($this->isCommandExist($name)) {
            $commandCategory = Command::where('name', $name)->first();
            return $commandCategory;
        } else {
            return json_decode('{"category_id" : "404"}');
        }
    }
}
