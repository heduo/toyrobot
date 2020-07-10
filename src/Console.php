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

    public function __construct() {
        $this->inputFile = __DIR__.'/data/good.input.txt';
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
    public function readFile(): array
    { 
        if (file_exists($this->inputFile)) {
            $content = trim(file_get_contents($this->inputFile)); // read file and trim its content
            if ($content==='') {
               throw new EmptyFileException('Input file is elmpty.');
            }
            $hasPlaceCommand = $this->hasPlaceCommand($content);

            if ($hasPlaceCommand!==false) {
                $commandsArray = preg_split("/(\r\n|\n|\r)/", $content); // split string by new line
                $commandsArray = array_map(function ($cmd){
                    return trim($cmd); // trim white space
                }, $commandsArray);
                $this->commands = $commandsArray;

                return $commandsArray;
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

    /**
     * check if file content contains PLACE command
     *
     * @param string $content
     * @return int | false position of PLACE command
     */
    public function hasPlaceCommand(string $content){
        
      return strpos(strtolower($content), 'place');

    }

    public function printCommands($commands)
    {
        foreach ($commands as $step  => $command) {
            printf("Command %d : %s \n", $step, $command);
        }
        
    }
}
