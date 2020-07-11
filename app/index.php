<?php

use App\ToyRobot;HR


// Read file and get commands
$console = new App\Console;
$console->readFile();

// Print all commands
echo "All commands:\n------------------------\n";
$console->printCommands($console->getCommands());

// Print sliced commands
echo "\nSliced commands from 'PLACE':\n------------------------\n";
$slicedCommands = $console->getSlicedCommands();
$console->printCommands($slicedCommands);

// Run sliced commands
$robot = new ToyRobot($slicedCommands);
$robot->run();