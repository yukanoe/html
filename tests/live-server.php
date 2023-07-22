<?php 
require __DIR__.'/vendor/autoload.php';

define("HTML_DIR",  './template-html');

// Routing
$filePath = $_SERVER['SCRIPT_NAME'] ?? '';
if( in_array($filePath, ['/','']) )
	$filePath = '/index.html';
$filePath = str_replace('.php', '.html', $filePath);
$ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
if($ext == 'html')
	$realFilePath = HTML_DIR. $filePath;
else
	$realFilePath = '.'.$filePath;
if(!is_file($realFilePath))
	exit("404 Error, Page Not Found");

$mime = mime_content_type($realFilePath);

// Header
if($mime)
	header("Content-type: {$mime}");
else
	header("Content-type: application/octet-stream");

// Payload
if( $mime == 'text/html' )
	eval((new \Yukanoe\HTML\TagManager)->read($realFilePath)->build()->get());
else
	echo file_get_contents($realFilePath);


