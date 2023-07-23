<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/template-php/index.php';

$username = $_GET['username'] ?? '';
if($username) {
    $avn['title']->text = "Hi, {$username}";
    $avn['text']->text  = "{$username}: Say anything.";
}