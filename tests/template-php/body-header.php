<?php
use \Yukanoe\HTML\Tag;
//FileSave: ./html-php/body-header.php
//Main Here
$av[1] = new Tag('html', [], '');
$av[2] = new Tag('body', [], '');
$av[1]->addChild($av[2]);
$av[3] = new Tag('h1', [], 'This is a Heading');
$av[2]->addChild($av[3]);
//Alias Here
