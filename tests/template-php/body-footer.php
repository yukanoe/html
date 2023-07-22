<?php
use \Yukanoe\HTML\Tag;
//FileSave: ./html-php/body-footer.php
//Main Here
$av[1] = new Tag('html', [], '');
$av[2] = new Tag('body', [], '');
$av[1]->addChild($av[2]);
$av[3] = new Tag('hr', [], '');
$av[2]->addChild($av[3]);
$av[4] = new Tag('div', [], 'Footer');
$av[2]->addChild($av[4]);
$av[5] = new Tag('hr', [], '');
$av[2]->addChild($av[5]);
//Alias Here
