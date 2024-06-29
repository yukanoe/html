<?php 

namespace Yukanoe\HTML;

class LiveServer
{
	private string $dir = ".";
    private $mimet = [
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',

        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
        'wav'  => 'audio/wave',
        'flac' => 'audio/flac',
        'ogg'  => 'audio/ogg',
        'mp3'  => 'audio/mpeg',
        'qt'   => 'video/quicktime',
        'mov'  => 'video/quicktime',
        'mp4'  => 'video/mp4',
        'webm' => 'video/webm',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',

        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'docx' => 'application/msword',
        'xlsx' => 'application/vnd.ms-excel',
        'pptx' => 'application/vnd.ms-powerpoint',


        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    ];

	function __construct()
	{
		return $this;
	}

    function getMineTypeByExtension($ext)
    {
        $ext = strtolower($ext);
        if( isset($this->mimet[$ext]) )
            return $this->mimet[$ext];
        return 'application/octet-stream';
    }

    function getMimeTypeByFileName($filename)
    {
        $FileArrTmp = explode( '.', $filename );
        return $this->getMineTypeByExtension(array_pop($FileArrTmp));
    }

	public function setPublicDir(string $dir='./'): LiveServer
	{
		$this->dir = $dir;
		return $this;
	}

	public function create(): void
	{
		$this->start();
	}

	public function start(): void
	{
		// Routing
		// default
		$uri = $_SERVER['REQUEST_URI'] ?? '/';
		if ($uri == '/')
			$uri = '/index';
		$file = $this->dir . $uri;
		$ext  = strtolower(substr($uri, -4));
		$htmlFile = $file;
		if ($ext != 'html')
			$htmlFile = "{$htmlFile}.html";
		//find html
		if (is_file($htmlFile)) {
			header("Content-type: text/html");
			$tagRoot = (new TagManager)->readRealTime($htmlFile);
			$tagRoot->flush();
			return;
		}
		// 404
		else if (!is_file($file)) {
			header("Content-type: text/html");
			echo("404 Error, Page Not Found {$file}");
			return;
		}
		$type = $this->getMimeTypeByFileName($file);
		header("Content-type: {$type}");
		echo file_get_contents($file);

	}

}
