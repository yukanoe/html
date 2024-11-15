<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Yukanoe\HTML\TagManager\Logger;

class LoggerTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset Logger mode before each test
        Logger::$mode = 'none';
    }

    public function testInfoModeNone()
    {
        Logger::$mode = 'none';
        $this->expectOutputString('');
        $logger = new Logger();
        $logger->info('This is an info message');
    }

    public function testInfoModeInfo()
    {
        Logger::$mode = 'info';
        $this->expectOutputString("\n[INFO] This is an info message\n");
        $logger = new Logger();
        $logger->info('This is an info message');
    }

    public function testDebugModeNone()
    {
        Logger::$mode = 'none';
        $this->expectOutputString('');
        $logger = new Logger();
        $logger->debug('This is a debug message');
    }

    public function testDebugModeDebug()
    {
        Logger::$mode = 'debug';
        $this->expectOutputString("\n[DEBUG] This is a debug message\n");
        $logger = new Logger();
        $logger->debug('This is a debug message');
    }

    public function testNewline()
    {
        Logger::$mode = 'info';
        Logger::$newline = '<br />';
        $this->expectOutputString("\n[INFO] This is an info message<br />");
        $logger = new Logger();
        $logger->info('This is an info message');
    }
}