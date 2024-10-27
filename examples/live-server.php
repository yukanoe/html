<?php

require __DIR__ . "/bootstrap.php";

use Yukanoe\HTML\LiveServer;

$server = new LiveServer;

$server->setPublicDir(__DIR__."/TemplateHTML");

$server->start();
