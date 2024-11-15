<?php

/**
 * LiveServer - A simple PHP live server
 * usage: php -S <host>:<port> LiveServer.php
 *
 * run: php -S localhost:8000 LiveServer.php
 */

namespace Yukanoe\HTML;

class LiveServer
{
    private string $dir = ".";
    private array $mimet = [
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

        // audio/video
        'wav' => 'audio/wave',
        'flac' => 'audio/flac',
        'ogg' => 'audio/ogg',
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',
        'mp4' => 'video/mp4',
        'webm' => 'video/webm'

    ];

    public function __construct()
    {
        Tag::$autoFlush = false;
    }

    public function getMineTypeByExtension($ext): string
    {
        $ext = strtolower($ext);
        if (isset($this->mimet[$ext]))
            return $this->mimet[$ext];
        return 'application/octet-stream';
    }

    public function getMimeTypeByFileName($filename): string
    {
        $FileArrTmp = explode('.', $filename);
        return $this->getMineTypeByExtension(array_pop($FileArrTmp));
    }

    public function setPublicDir(string $dir = './'): LiveServer
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
        $ext = strtolower(substr($uri, -4));
        $htmlFile = $file;
        if ($ext != 'html')
            $htmlFile = "{$htmlFile}.html";
        //find html
        if (is_file($htmlFile)) {
            $this->setHeader("Content-type: text/html");
            $tagRoot = (new TagManager)->readRealTime($htmlFile);
            $tagRoot?->flush();
            return;
        } // 404

        if (!is_file($file)) {
            $this->setHeader("Content-type: text/html");
            echo("404 Error, Page Not Found {$file}");
            return;
        }

        $type = $this->getMimeTypeByFileName($file);
        $this->setHeader("Content-type: {$type}");
        echo file_get_contents($file);

    }

    public function setHeader(string $header): LiveServer
    {
        if (PHP_SAPI !== 'cli') {
            header($header);
        }
        return $this;
    }

}
