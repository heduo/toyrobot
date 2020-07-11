<?php

use PHPUnit\Framework\TestCase;
use App\Exceptions\EmptyCommandsException;
use App\Exceptions\InvalidCommandException;
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
   public function ready_to_run()
   {
       $commands = $this->robot->getCommands();
       $this->assertIsArray($commands);
       $readyToRuN = $this->robot->readyToRun();
       $this->assertEquals(true, $readyToRuN);
   }

   /** 
    * Should throw InvalidTableSizeException when table size is 0
    * @test 
    */
   public function throw_exception_when_table_size_is_invalid()
   {
       $this->expectException(InvalidTableSizeException::class);
       $commands = $this->robot->getCommands();
       $this->robot = new ToyRobot($commands, 0);
   }

    /** 
     * Should throw EmptyCommandsException when commands is empty
     * @test 
     * */
   public function throw_exception_when_commands_is_empty()
   {
       $this->expectException(EmptyCommandsException::class);
       $this->robot = new ToyRobot([]);
   }

   /** 
    * Should throw NoPlaceCommandException when no PLACE found in commands
    * @test 
    */
   public function throw_exception_when_no_place_command()
   {
       $this->expectException(NoPlaceCommandException::class);
       $this->robot = new ToyRobot(['MOVE', 'LEFT', 'MOVE']);
   }

   /** 
    * Shoud correctly parse PLACE command string to a postion array
    * @test 
    */
   public function parse_correct_format_place_command()
   {
       $placeCommand = 'PLACE 1,2,EAST';
       $this->robot = new ToyRobot([$placeCommand]);
       $cmd = $this->robot->parsePlaceCommand($placeCommand);
       $expect = ['x' => 1, 'y'=> 2, 'face'=> 'EAST' ];
       $this->assertEquals($expect, $cmd);
   }

   /** 
    * Can initialise current position with valid PLACE command
    * @test
    */
   public function can_init_current_position_with_correct_format_place_command()
   {
    $placeCommand = 'PLACE 1,2,EAST';
    $this->robot = new ToyRobot([$placeCommand]);
    $this->robot->initCurrentPosition($placeCommand);
    $currentPosition = $this->robot->getCurrentPosition();
    $this->assertEquals(['x' => 1, 'y' => 2, 'face' => 'EAST'], $currentPosition);
   }

   /** 
    * Can move based on a moveable position
    * @test 
    */
   public function can_move_based_on_movable_position()
   {
       $testPos = ['x' => 0, 'y' => 1, 'face' => 'NORTH'];
       $this->robot->setCurrentPosition($testPos);
       
       $newPos = $this->robot->canMove();
       $expectPos = ['x'=>0, 'y' => 2, 'face' => 'NORTH'];
       $this->assertEquals($expectPos, $newPos);
   }

   /** 
    * Should remain still if try to move from an unmovable postion
    * @test 
    */
   public function can_not_move_based_on_unmovable_position()
   {
    $testPos = ['x' => 0, 'y' => 5, 'face' => 'NORTH'];
    $this->robot->setCurrentPosition($testPos);
    
    $canMove = $this->robot->canMove();
    $this->assertEquals(false, $canMove);
    $this->assertEquals($testPos, $this->robot->getCurrentPosition()); // current position remain unchanged
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

   /** 
    * Should throw InvalidCommandException when execute invalid command 'UP'
    * @test 
    */
   public function throw_invalid_command_exception()
   {
    $this->expectException(InvalidCommandException::class);

    $testPos = ['x' => 0, 'y' => 1, 'face' => 'NORTH'];
    $this->robot->setCurrentPosition($testPos);
    $this->robot->execute('UP'); // invalid UP command
   }

}