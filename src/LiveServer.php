<?php 
namespace Yukanoe\HTML;
/**
 *
 * live-server.php:
 * (new \Yukanoe\Tag\LiveServer)->create();
 *
 * php -S localhost:8080 live-server.php
 *
*/


class LiveServer
{
	private string $rootDir = ".";

	function __construct()
	{
		return $this;
	}

	public function setRootDir(string $dir='./'): LiveServer
	{
		$this->rootDir = $dir;
		return $this;
	}

	public function create(): void
	{
		// Routing
		// default
		$urlPath = $_SERVER['SCRIPT_NAME'] ?? '';
		if( in_array($urlPath, ['/','']) )
			$urlPath = '/index.html';
		// get full path
		$filePath = $this->rootDir . $urlPath;

		// 404
		if(!is_file($filePath)) {
			header("Content-type: text/html");
			exit("404 Error, Page Not Found {$filePath}");
		}

		// file exists
		$mime = mime_content_type($filePath);
		//header
		if($mime){
			header("Content-type: {$mime}");
		} else {
			header("Content-type: application/octet-stream");
		}
		// Payload
		if( $mime == 'text/html' ){
			$tagRoot = (new TagManager)->readRealTime($filePath);
			$tagRoot->flush();
		} else {
			echo file_get_contents($filePath);
		}
	}

}
