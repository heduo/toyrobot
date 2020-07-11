<?php

require_once 'config.php';

use App\ToyRobot;

/**
 *  1. Commands are designed to read from a file
 *  2. Input file is designed to only read within './public/input/' folder
 *  3. Input file name can be passed from command line. 
 *      For example: "php ./public/index.php myinput.txt", it will try to find 'myinput.txt' in './public/input/' folder
 *  4. If no input file is passed from command line, then the default input file is 'default.input.txt'
 *  5. Input file config is located in 'app/config.php'
 * 
 */
if (!empty($argv[1])) {
    $inputFile = $default_input_dir."/".$argv[1];
    $input = $argv[1];
}else{
    $inputFile = DEFAULT_INPUT_FILE;
    $input = $defualt_input_name;
}

// Read file and get commands
$console = new App\Console($inputFile);
echo "\n********** Toy Robot Simulator ********** \n";
echo "\nReading commands from $input ...\n";
$console->readFile();

// Print all commands
echo "\nAll commands:\n------------------------\n";
$console->printCommands($console->getCommands());                                              

// Print sliced commands
echo "\nSliced commands from 1st 'PLACE':\n------------------------\n";
$slicedCommands = $console->getSlicedCommands();
$console->printCommands($slicedCommands);

// Run sliced commands
$robot = new ToyRobot($slicedCommands);
$robot->run();
