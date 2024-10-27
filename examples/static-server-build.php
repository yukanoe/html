<?php

require __DIR__.'/bootstrap.php';

use Yukanoe\HTML\TagManager;

$TagManager = new TagManager;

$TagManager->autoBuild(
    __DIR__ . '/TemplateHTML', //input
    __DIR__ . '/TemplatePHP'  //output
);
