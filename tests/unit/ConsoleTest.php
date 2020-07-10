<?php

use PHPUnit\Framework\TestCase;
use App\Exceptions\FileNotExistsException;
use App\Exceptions\NoPlaceCommandException;
use App\Exceptions\EmptyFileException;

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
      $commands = $this->console->readFile();
       $this->assertIsArray($commands);
   }

   /** @test */
   public function throw_exception_when_input_file_not_exists()
   {
       $this->expectException(FileNotExistsException::class);
       $this->console->setInputFile('file_not_exist.txt');
       $this->console->readFile();
   }

   /** @test */
   public function throw_exception_when_input_file_is_empty()
   {
    $this->expectException(EmptyFileException::class);
    $empty_file =  dirname(dirname(__DIR__)).'/src/data/empty.input.txt';
    $this->console->setInputFile($empty_file);
    $this->console->readFile();
   }

   /** @test */
   public function throw_exception_when_input_commands_has_no_place_command()
   {
       $this->expectException(NoPlaceCommandException::class);
       $no_place_input =  dirname(dirname(__DIR__)).'/src/data/no_place_command.input.txt';
       $this->console->setInputFile($no_place_input);
       $this->console->readFile();
       
   }


}