<?php

namespace App;

use App\Exceptions\FileNotExistsException;
use App\Exceptions\NoPlaceCommandException;
use App\Exceptions\EmptyFileException;


/**
 * Console class is mainly used for reading input file and parse its content to an commands array
 */

class Console
{
    private string $inputFile;
    private array $commands;
    private array $slicedCommands;

    public function __construct($file=null) {
       if (!$file) {
        $this->inputFile = __DIR__.'/data/good.input.txt';
       } else{
        $this->inputFile = $file;
       }
    }



    public function setInputFile(string $file):void
    {
        $this->inputFile = $file;
    }

    /**
     * read input file, process content to an commands array
     *
     * @return array commands array
     */
    public function readFile()
    { 
        if (file_exists($this->inputFile)) {
            $content = trim(file_get_contents($this->inputFile)); // read file and trim its content
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

    public function stringToCommandsArray(string $commands):array
    {
        $commandsArray = preg_split("/(\r\n|\n|\r)/", $commands); // split string by new line
        $commandsArray = array_map(function ($cmd){
            return trim($cmd); // trim white space
        }, $commandsArray);

        return $commandsArray;
    }

    
}
