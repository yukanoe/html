<?php
use \Yukanoe\HTML\Tag;
//FileSave: ./html-php/head.php
//Main Here
$av[1] = new Tag('html', [], '');
$av[2] = new Tag('head', [], '');
$av[1]->addChild($av[2]);
$av[3] = new Tag('title', ['data-yukanoe-id'=>'title'], 'Page Title');
$av[2]->addChild($av[3]);
//Alias Here
$avn['title'] = $av[3];
