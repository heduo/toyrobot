<?php

use PHPUnit\Framework\TestCase;
use App\Console;
use App\ToyRobot;

class ToyRobotTest extends TestCase
{
   protected $robot;
   
   protected function setUp():void
   {
    $console = new Console;
    $console->readFile();
    $commands = $console->getSlicedCommands();
    $this->robot = new ToyRobot($commands);
   }

   /** @test */
   public function has_commands()
   {
       $commands = $this->robot->getCommands();
       var_dump($commands);
       $this->assertIsArray($commands);
   }


}