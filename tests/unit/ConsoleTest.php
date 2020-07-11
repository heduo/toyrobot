<?php

use PHPUnit\Framework\TestCase;
use App\Exceptions\FileNotExistsException;
use App\Exceptions\NoPlaceCommandException;
use App\Exceptions\EmptyFileException;
use App\Exceptions\BadInputFileFormatException;

class ConsoleTest extends TestCase
{
   protected $console;
   protected function setUp():void
   {
    $this->console = new App\Console;
   }
   /** @test */
   public function can_read_valid_input_file()
   {
       $this->console->readFile();
       $this->assertIsArray($this->console->getCommands());
       $this->assertIsArray($this->console->getSlicedCommands());
   }

   /** 
    * Should throw FileNotExistsException when input file does't exist
    * @test 
    */
   public function throw_exception_when_input_file_not_exists()
   {
       $this->expectException(FileNotExistsException::class);
       $this->console->setInputFile('file_not_exist.txt');
       $this->console->readFile();
   }

   /** 
    * Should throw EmptyFileException when the file is empty
    * @test 
    */
   public function throw_exception_when_input_file_is_empty()
   {
    $this->expectException(EmptyFileException::class);
    $empty_file =  __DIR__.'/../../public/input/empty.input.txt';
    $this->console->setInputFile($empty_file);
    $this->console->readFile();
   }

   /** 
    * Should throw NoPlaceCommandException when input file doesn't contain any PLACE command
    * @test 
    */
   public function throw_exception_when_input_commands_has_no_place_command()
   {
       $this->expectException(NoPlaceCommandException::class);
       $no_place_input =   __DIR__.'/../../public/input/no_place_command.input.txt';
       $this->console->setInputFile($no_place_input);
       $this->console->readFile();
       
   }

   /** 
    * Should throw BadInputFileFormatException when input file is not in '.txt' format
    * @test 
    */
   public function throw_exception_when_file_format_is_not_correct()
   {
       $this->expectException(BadInputFileFormatException::class);
       $badFile = __DIR__.'/../../public/input/bad_format.input.csv';
       $this->console->setInputFile($badFile);
       $this->console->readFile();
   }

}