## 1. My Dev Environment
- OS: MacOS Catalina 10.15.5
- PHP 7.4.7
- composer 1.10.8


## 2. Installation
#### 2.1 Download from github

```
git clone https://github.com/heduo/toyrobot.git
```

#### 2.2 Install phpunit from composer
```
cd toyrobot
composer require phpunit/phpunit
```

#### 2.3 Autoload set up
```
composer dump-autoload -o
```

## 3. How to Run the app ?

First make sure you are in root project directory

```
cd toyrobot
```

There are two ways to run the app. 
#### 3.1 Run with its own default input file
The default file is **'public/input/default.input.txt'** if you don't specify the file name
```
php public/index.php
```

#### 3.2 Run with your own input file
The default input folder is **'public/input'**, after you add your own input file in that folder, run the command following below format

```
php public/index.php my_input_file.txt
```

## 4. How to run unit tests ?

First make sure you are in root project directory

```
cd toyrobot
```

Then run the command below
```
./vendor/bin/phpunit
```

## 5. Where to change the app configuration ?
- 'app/config.php'

## 6. Sample input files
#### Input files in "public/input"
| File                                    | Purpose | Expected Exception (if any) |
| -----                                   | ------- |  -------------- |
|  a.input.txt, b.input.txt, c.input.txt  | samples from PROBLEM.md  | |
|bad_format.input.csv | for '.txt' format check|   BadInputFileFormatException          |
|default.input.txt|default input file| |
|empty.input.txt | for epmty file check |EmptyFileException |
|no_place_command.input.txt|for no PLACE command check| NoPlaceCommandException|
|try_far_move.input.txt |try far move and still remain on table top| |
|invalid_command.input.txt | try for invalid command, such as 'UP', 'DOWN'...| InvalidCommandException |

