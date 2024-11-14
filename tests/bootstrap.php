<?php

include(__DIR__."/../vendor/autoload.php");

$text = "= [INFO]: Yukanoe\\HTML =";
$line = str_repeat("=", strlen($text));
echo "\n{$line}";;
echo "\n{$text}";
echo "\n{$line}\n";
