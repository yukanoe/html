<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Yukanoe\HTML\TagManager\IO;

class IOTest extends TestCase
{
    private string $htmlFile = __DIR__ . '/resource/TemplateHTML/index.html';
    public function testReadHTMLStringFromFile()
    {
        $io = new IO();
        $result = $io->readHTMLStringFromFile($this->htmlFile);
        $this->assertIsString($result);
        $this->assertStringContainsString('<html', $result);
    }

    public function testReadHTMLFile()
    {
        $io = new IO();
        $result = $io->readHTMLFile($this->htmlFile);
        $this->assertInstanceOf(\DOMElement::class, $result);
    }

    public function testReadHTMLRaw()
    {
        $io = new IO();
        $html = '<!DOCTYPE html><html lang="en"><head><title>Test</title></head><body></body></html>';
        $result = $io->readHTMLRaw($html);
        $this->assertInstanceOf(\DOMElement::class, $result);
    }

    public function testView()
    {
        $io = new IO();
        $this->expectOutputString("Hello World\n");
        $io->view(['Hello World']);
    }

    public function testToString()
    {
        $io = new IO();
        $statements = ['echo "Hello World";'];
        $aliasStatements = [];
        $phpfile = __DIR__ . '/output/render.php';
        $namespace = 'Tests';

        $result = $io->toString($statements, $aliasStatements, $phpfile, $namespace);
        $this->assertStringContainsString('namespace Tests;', $result);
        $this->assertStringContainsString('echo "Hello World";', $result);
    }
}