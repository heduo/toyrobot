<?php
require_once dirname(__DIR__).'/vendor/autoload.php';

// Read file and get commands
$console = new App\Console;
$commands = $console->readFile();

// Print all parsed commands
$console->printCommands($commands);

// Trim commands to keep only valid commands starting from PLACE

// Run trimmed commands


// Print result