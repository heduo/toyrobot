<?php

namespace App;

class ToyRobot
{
    private int $tableSize;
    private array $commands;

    public function __construct(array $commands, $tableSize=5) {
        $this->commands = $commands;
        $this->tableSize = $tableSize;
    }

    public function getCommands():array
    {
       return $this->commands;
    }
}
