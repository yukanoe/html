<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Yukanoe\HTML\LiveServer;

class LiveServerTest extends TestCase
{
    public function testGetMineTypeByExtension()
    {
        $server = new LiveServer();
        $this->assertEquals('text/html', $server->getMineTypeByExtension('html'));
        $this->assertEquals('application/json', $server->getMineTypeByExtension('json'));
        $this->assertEquals('application/octet-stream', $server->getMineTypeByExtension('unknown'));
    }

    public function testGetMimeTypeByFileName()
    {
        $server = new LiveServer();
        $this->assertEquals('text/html', $server->getMimeTypeByFileName('index.html'));
        $this->assertEquals('application/json', $server->getMimeTypeByFileName('data.json'));
        $this->assertEquals('application/octet-stream', $server->getMimeTypeByFileName('file.unknown'));
    }

    public function testSetPublicDir()
    {
        $server = new LiveServer();
        $server->setPublicDir('/public');
        $this->assertEquals('/public', $this->getPrivateProperty($server, 'dir'));
    }

    public function testStart()
    {
        $server = new LiveServer();
        $server->setPublicDir(__DIR__ . '/public');
        $server->create();
        $this->expectOutputString('404 Error, Page Not Found ' . __DIR__ . '/public/index');
    }

    private function getPrivateProperty($object, $property)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

}