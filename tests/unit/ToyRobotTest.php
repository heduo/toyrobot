<?php

use PHPUnit\Framework\TestCase;
use App\Console;
use App\Exceptions\EmptyCommandsException;
use App\Exceptions\InvalidTableSizeException;
use App\Exceptions\NoPlaceCommandException;
use App\ToyRobot;

class ToyRobotTest extends TestCase
{
   protected $robot;
   protected $console;

   protected function setUp():void
   {
    $this->console = new Console;
    $this->console->readFile();
    $commands = $this->console->getSlicedCommands();
    $this->robot = new ToyRobot($commands);
   }

   /** @test */
   public function ready_run()
   {
       $commands = $this->robot->getCommands();
       $this->assertIsArray($commands);
       $readyToRuN = $this->robot->readyToRun();
       $this->assertEquals(true, $readyToRuN);
   }

   /** @test */
   public function throw_exception_when_table_size_is_invalid()
   {
       $this->expectException(InvalidTableSizeException::class);
       $this->robot = new ToyRobot($this->console->getSlicedCommands(), 0);
   }

    /** @test */
   public function throw_exception_when_commands_is_empty()
   {
       $this->expectException(EmptyCommandsException::class);
       $this->robot = new ToyRobot([]);
   }

   /** @test */
   public function throw_exception_when_no_place_command()
   {
       $this->expectException(NoPlaceCommandException::class);
       $this->robot = new ToyRobot(['MOVE', 'LEFT', 'MOVE']);
   }

   /** @test */
   public function parse_correct_format_place_command()
   {
       $placeCommand = 'PLACE 1,2,EAST';
       $this->robot = new ToyRobot([$placeCommand]);
       $cmd = $this->robot->parsePlaceCommand($placeCommand);
       $expect = ['x' => 1, 'y'=> 2, 'face'=> 'EAST' ];
       $this->assertEquals($expect, $cmd);
   }

   /** @test */
   public function can_init_current_position_with_correct_format_place_command()
   {
    $placeCommand = 'PLACE 1,2,EAST';
    $this->robot = new ToyRobot([$placeCommand]);
    $this->robot->initCurrentPosition($placeCommand);
    $currentPosition = $this->robot->getCurrentPosition();
    $this->assertEquals(['x' => 1, 'y' => 2, 'face' => 'EAST'], $currentPosition);
   }
}