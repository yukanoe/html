<?php
require __DIR__.'/vendor/autoload.php';

use \Yukanoe\HTML\TagManager;

$TagManager = new TagManager;
$TagManager->autoBuild('./template-html', './template-php'); //input, output
