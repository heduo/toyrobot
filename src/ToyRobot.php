<?php

namespace App;

use App\Exceptions\BadPlaceCommandFormatException;
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

        $this->readyToRun(); // check if ready to run when create object
    }

    public function getCommands():array
    {
       return $this->commands;
    }

    public function run()
    {
       // initialise current position with first PLACE command
       $this->initCurrentPosition($this->getCommands()[0]);

       // execute commands

        
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

    public function initCurrentPosition(string $command)
    {
        $this->currentPosition = $this->parsePlaceCommand($command);
       
    }

    public function parsePlaceCommand(string $command)
    {
        $cmd = explode(" ", $command);
        if (strtolower($cmd[0])!=='place') {
           throw new BadPlaceCommandFormatException('Not PLACE Command');
        }
        $pos = explode(",",$cmd[1]);
        $directions = array("east", "west", "south", "north");
        if (!is_int(intval($pos[0])) || !is_int(intval($pos[1])) || !in_array(strtolower($pos[2]), $directions)){
            throw new BadPlaceCommandFormatException($pos[0].$pos[1].$pos[2]);
        }

        return [
          
                'x' => $pos[0], 
                'y' => $pos[1], 
                'face' => $pos[2],
        ];
    }

    public function move()
    {
        # code...
    }

    public function rotate(string $direction)
    {
        # code...
    }
   
}
