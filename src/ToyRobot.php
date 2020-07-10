<?php

namespace App;

use App\Exceptions\EmptyCommandsException;
use App\Exceptions\NoPlaceCommandException;
use App\Exceptions\InvalidTableSizeException;

class ToyRobot
{
    private int $tableSize;
    private array $commands;
    private array $currentPosition;

    public function __construct(array $commands, $tableSize=5) {
        $this->commands = $commands;
        $this->tableSize = $tableSize;

        $readyToRun = $this->readyToRun();
    }

    public function getCommands():array
    {
       return $this->commands;
    }

    public function run()
    {
       // initialise current position with first PLACE command
       
        
    }

    
    public function readyToRun():bool
    {
        // 1. check if table size is greater than 0
        $tableSize = $this->tableSize;
        $validTableSize =(is_int($tableSize) && $tableSize >0) ? true : false;

        if (!$validTableSize) {
           throw new InvalidTableSizeException;
        }
        
        // 2. check if commands array is empty
        $notEmptyCommands = count($this->commands);
        if (!$notEmptyCommands) {
            throw new EmptyCommandsException;
            
        }

        // 3. check if first command is PLACE command
        $placePos = strpos(strtolower($this->commands[0]), 'place');
        if ($placePos===false){
             throw new NoPlaceCommandException;
        }

        return $validTableSize && $notEmptyCommands && ($placePos!==false) ? true : false;


    }
}
