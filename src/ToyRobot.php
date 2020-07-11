<?php

namespace App;

use App\Exceptions\BadPlaceCommandFormatException;
use App\Exceptions\EmptyCommandsException;
use App\Exceptions\NoPlaceCommandException;
use App\Exceptions\InvalidTableSizeException;

class ToyRobot
{
    private int $tableSize; // default is 5
    private array $commands; // its first command should be PLACE, otherwise it will throw exception
    private array $currentPosition;

    public function __construct(array $commands, $tableSize = 5)
    {
        $this->commands = $commands;
        $this->tableSize = $tableSize;

        $this->readyToRun(); // check if ready to run when create object
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    public function getCurrentPosition():array
    {
        return $this->currentPosition;
    }

    public function setCurrentPosition(array $position):void
    {
        $this->currentPosition = $position;
    }

    /**
     * Simulate the robot to run commands 
     *
     * @return void
     */
    public function run():void
    {
        $commands = $this->getCommands();
        // initialise current position with first PLACE command
        $this->initCurrentPosition($commands[0]);
        $restCommands = array_slice($commands, 1);
        // execute the rest commands
        foreach ($restCommands as $command) {
            $this->execute($command);
        }
    }

    /**
     * Check if it's ready to run the commands
     *
     * @return boolean
     */
    public function readyToRun(): bool
    {
        // 1. check if table size is greater than 0
        $tableSize = $this->tableSize;
        $validTableSize = (is_int($tableSize) && $tableSize > 0) ? true : false;

        if (!$validTableSize) {
            throw new InvalidTableSizeException;
        }

        // 2. check if commands array is empty
        $notEmptyCommands = count($this->commands);
        if (!$notEmptyCommands) {
            throw new EmptyCommandsException;
        }

        // 3. check if first command is PLACE 
        // It's designed that commands from input are always sliced already if neccesorry so that the first command is PLACE
        $placePos = strpos(strtolower($this->commands[0]), 'place');
        if ($placePos === false) {
            throw new NoPlaceCommandException;
        }

        return $validTableSize && $notEmptyCommands && ($placePos !== false) ? true : false;
    }

    /**
     * Execute the first PLACE command
     *
     * @param string $command
     * @return void
     */
    public function initCurrentPosition(string $command)
    {
        $initPosition = $this->parsePlaceCommand($command);
        $this->currentPosition = $initPosition;
    }

    /**
     * Parse valid PLACE command string to postion array 
     *
     * @param string $command
     * @return array position
     * 
     * PLACE command format:"int, int, 'EAST'|'WEST'|'SOUTH'|'NORTH'"
     */
    public function parsePlaceCommand(string $command):array
    {
        $cmd = explode(" ", $command); 
        if (strtolower($cmd[0]) !== 'place') {
            throw new BadPlaceCommandFormatException();
        }
        $pos = explode(",", $cmd[1]);
        $faces = array("east", "west", "south", "north");
        if (!is_int(intval($pos[0])) || !is_int(intval($pos[1])) || !in_array(strtolower($pos[2]), $faces)) {
            throw new BadPlaceCommandFormatException(); 
        }

        // posiiton
        return [
            'x' => intval($pos[0]),
            'y' => intval($pos[1]),
            'face' => strtoupper($pos[2]),
        ];
    }

    /**
     * Execute PLACE command
     *
     * @param string $command
     * @return void
     */
    public function place(string $command): void
    {
        $pos = $this->parsePlaceCommand($command);
        $this->currentPosition = $pos;
    }

    /**
     * Execute MOVE command to update current position
     *
     * @return void
     */
    public function move(): void
    {
        $newPos = $this->canMove();
        if ($newPos) {
            $this->currentPosition = $newPos;
        }
    }

    /**
     * Execute LEFT command
     *
     * @return void
     */
    public function left():void
    {
        $face = $this->currentPosition['face'];

        switch (strtoupper($face)) {
            case 'NORTH':
                $this->currentPosition['face'] = "WEST";
                break;

            case 'SOUTH':
                $this->currentPosition['face'] = "EAST";
                break;

            case 'WEST':
                $this->currentPosition['face'] = "SOUTH";
                break;
            case 'EAST':
                $this->currentPosition['face'] = "NORTH";
                break;

            default:
                throw new \Exception("Invalid Rotate Command");
                break;
        }
    }

    /**
     * Execute RIGHT command
     *
     * @return void
     */
    public function right():void
    {
        $face = $this->currentPosition['face'];

        switch (strtoupper($face)) {
            case 'NORTH':
                $this->currentPosition['face'] = "EAST";
                break;

            case 'SOUTH':
                $this->currentPosition['face'] = "WEST";
                break;

            case 'WEST':
                $this->currentPosition['face'] = "NORTH";
                break;
            case 'EAST':
                $this->currentPosition['face'] = "SOUTH";
                break;

            default:
                throw new \Exception("Invalid Rotate Command");
                break;
        }
    }

    /**
     * Execute REPORT command
     *
     * @return void
     */
    public function report():void
    {
        $pos = $this->currentPosition;
        printf("\nReport Output: %d,%d,%s\n", $pos['x'], $pos['y'], $pos['face']);
    }

    /**
     * Execute command
     *
     * @param string $command
     * @return void
     */
    public function execute(string $command)
    {
        $cmd = explode(' ', $command);
        $cmdType = $cmd[0];
        switch (strtolower($cmdType)) {
            case 'place':
                $this->place($command);
                break;
            case 'move':
                $this->move();
                break;

            case 'left':
                $this->left();
                break;
            case 'right':
                $this->right();
                break;
            case 'report':
                $this->report();
                break;
            default:
                # code...
                break;
        }
    }

    /**
     * Check if it can move forward, return new postion if it can, otherwise return false
     *
     * @return array | false
     */
    public function canMove()
    {
        $currentPosition = $this->currentPosition;
        $tableSize = $this->tableSize;
        $x = intval($currentPosition['x']);
        $y = intval($currentPosition['y']);
        $face = strtoupper($currentPosition['face']);

        if ($face === 'NORTH' && $tableSize > $y) {
            return [
                'x' => $x,
                'y' => $y + 1,
                'face' => $face
            ];
        }

        if ($face === 'SOUTH' && $y > 0) {
            return [
                'x' => $x,
                'y' => $y - 1,
                'face' => $face
            ];
        }

        if ($face === 'EAST' && $tableSize > $x) {
            return [
                'x' => $x + 1,
                'y' => $y,
                'face' => $face
            ];
        }
        if ($face === 'WEST' && $x > 0) {
            return [
                'x' => $x - 1,
                'y' => $y,
                'face' => $face
            ];
        }

        return false;
    }
}
