<?php

use PHPUnit\Framework\TestCase;
use App\Exceptions\EmptyCommandsException;
use App\Exceptions\InvalidTableSizeException;
use App\Exceptions\NoPlaceCommandException;
use App\ToyRobot;

class ToyRobotTest extends TestCase
{
   protected $robot;

   protected function setUp():void
   {
    $commands = ['PLACE 1,2,EAST', 'MOVE', 'MOVE', 'LEFT', 'MOVE', 'REPORT'];
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
       $commands = $this->robot->getCommands();
       $this->robot = new ToyRobot($commands, 0);
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

   /** @test */
   public function can_move_north_based_on_movable_postion()
   {
       $testPos = ['x' => 0, 'y' => 1, 'face' => 'NORTH'];
       $this->robot->setCurrentPosition($testPos);
       $this->assertEquals($testPos, $this->robot->getCurrentPosition());
       
       $newPos = $this->robot->canMove();
       $expectPos = ['x'=>0, 'y' => 2, 'face' => 'NORTH'];
       $this->assertEquals($expectPos, $newPos);
   }

   /** @test */
   public function can_turn_left()
   {
    $testPos = ['x' => 0, 'y' => 1, 'face' => 'NORTH'];
    $this->robot->setCurrentPosition($testPos);
    
    $this->robot->left();
    $expectPos = ['x'=>0, 'y' => 1, 'face' => 'WEST'];
    $this->assertEquals($expectPos, $this->robot->getCurrentPosition());
   }

   /** @test */
   public function can_turn_right()
   {
    $testPos = ['x' => 0, 'y' => 1, 'face' => 'NORTH'];
    $this->robot->setCurrentPosition($testPos);
    
    $this->robot->right();
    $expectPos = ['x'=>0, 'y' => 1, 'face' => 'EAST'];
    $this->assertEquals($expectPos, $this->robot->getCurrentPosition());
   }

   /** @test */
   public function can_report()
   {
    $this->expectOutputString("\nReport Output: 0,1,NORTH\n");
    
    $testPos = ['x' => 0, 'y' => 1, 'face' => 'NORTH'];
    $this->robot->setCurrentPosition($testPos);
    $this->robot->report();
    
   }

}