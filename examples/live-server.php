<?php

require __DIR__ . "/bootstrap.php";

$server = new \Yukanoe\HTML\LiveServer;
$server->setPublicDir(__DIR__."/TemplateHTML");
$server->start();
