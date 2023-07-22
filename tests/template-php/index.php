<?php
use \Yukanoe\HTML\Tag;
//FileSave: ./html-php/index.php
//Main Here
$av[1] = new Tag('html', [], '');
$av[2] = new Tag('head', [], '');
$av[1]->addChild($av[2]);
$av[3] = new Tag('title', ['data-yukanoe-id'=>'title'], 'Page Title');
$av[2]->addChild($av[3]);
$av[4] = new Tag('body', [], '');
$av[1]->addChild($av[4]);
$av[5] = new Tag('h1', [], 'This is a Heading');
$av[4]->addChild($av[5]);
$av[6] = new Tag('p', ['data-yukanoe-id'=>'message'], 'This is a paragraph.');
$av[4]->addChild($av[6]);
$av[7] = new Tag('hr', [], '');
$av[4]->addChild($av[7]);
$av[8] = new Tag('div', [], 'Footer');
$av[4]->addChild($av[8]);
$av[9] = new Tag('hr', [], '');
$av[4]->addChild($av[9]);
//Alias Here
$avn['title'] = $av[3];
$avn['message'] = $av[6];
