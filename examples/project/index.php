<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/template-php/index.php';

$username = 'admin' ?? '';
if($username) {
    $avn['title']->text = "Hi, {$username}";
    $avn['text']->text  = "{$usename}: Say anything.";
}