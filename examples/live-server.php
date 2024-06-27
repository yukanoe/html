<?php

require __DIR__ . "/bootstrap.php";

$server = new \Yukanoe\HTML\LiveServer;
$server->setRootDir(__DIR__."/TemplateHTML");
$server->start();
