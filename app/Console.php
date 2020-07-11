<?php

namespace App;

use App\Exceptions\BadInputFileFormatException;
use App\Exceptions\FileNotExistsException;
use App\Exceptions\NoPlaceCommandException;
use App\Exceptions\EmptyFileException;
use App\Helpers\StringHelper;


/**
 * Console class is mainly used for reading input file and parse its content to an commands array
 * It's normally used before Toy Robot run commands
 */

class Console
{
    use StringHelper;
    private string $inputFile;
    private array $commands;
    private array $slicedCommands; // commands starting from 1st PLACE command

    public function __construct($file=null) {
       require_once "config.php";
       if (!$file) {
        $this->inputFile = DEFAULT_INPUT_FILE;
       } else{
        $this->inputFile = $file;
       }
    }

    public function setInputFile(string $file):void
    {
        $this->inputFile = $file;
    }

    /**
     * Read input file, process content , then set commands and sliced commands
     *
     * @return void
     */
    public function readFile():void
    { 
        $inputFile = $this->inputFile;
        if (file_exists($inputFile)) {
            // check file format is '.txt'
            $isTxtFormat = $this->isTxtFormat($inputFile);
            if ($isTxtFormat!=1) {
                throw new BadInputFileFormatException('Input file format should be ".txt"');
            }
            $content = trim(file_get_contents($inputFile)); // read file and trim its content
            if ($content==='') {
               throw new EmptyFileException('Input file is elmpty.');
            }
            $hasPlaceCommand = $this->hasPlaceCommand($content);

            if ($hasPlaceCommand!==false) {
                $this->slicedCommands = $this->stringToCommandsArray(substr($content, $hasPlaceCommand)); // slice commands and keep the part from PLACE command
                $this->commands =  $this->stringToCommandsArray($content);
            }else{
                throw new NoPlaceCommandException('No PLACE command found');
            }
            
        }else{
            throw new FileNotExistsException("Invalid input file path/name");
            
        }
        
    }

    public function isTxtFormat($file)
    {
        $isTxtFormat = $this->endsWith($file, '.txt');
        return $isTxtFormat;

    }

    public function getCommands():array
    {
        return $this->commands;
    }

    public function getSlicedCommands():array
    {
        return $this->slicedCommands;
    }

    /**
     * check if file content contains PLACE command
     *
     * @param string $content
     * @return int | false position of PLACE command
     */
    public function hasPlaceCommand(string $content){
        
      return strpos(strtolower($content), 'place');

    }

    public function printCommands(array $commands)
    {
        foreach ($commands as $step  => $command) {
            printf("Command %d : %s \n", $step, $command);
        }
        
    }

    /**
     * Convert string commands to array
     *
     * @param string $commands
     * @return array
     */
    public function stringToCommandsArray(string $commands):array
    {
        $commandsArray = preg_split("/(\r\n|\n|\r)/", $commands); // split string by new line
        $commandsArray = array_map(function ($cmd){
            return trim($cmd); // trim white space
        }, $commandsArray);

        return $commandsArray;
    }

    
}
