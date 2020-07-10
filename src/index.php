<?php
require_once dirname(__DIR__).'/vendor/autoload.php';

$console = new App\Console;
$commands = $console->readFile();
$console->printCommands();