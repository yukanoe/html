<?php 
require __DIR__ . '/bootstrap.php';
require __DIR__ . '/TemplatePHP/index.php';

$avn['title']->text   = "Static-Server";
$avn['message']->text = "Hi, Static-Server";

$username = $_GET['username'] ?? '';
if($username) {
    $avn['title']->text   = "Hi, {$username}";
    $avn['message']->text = "{$username}: Say anything.";
}
