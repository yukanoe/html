<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Yukanoe\HTML\TagManager\Compiler;
use Yukanoe\HTML\Tag;
use Yukanoe\HTML\TagManager\IO;

class CompilerTest extends TestCase
{
    public function testGetTagRoot()
    {
        $compiler = new Compiler();
        $this->assertNull($compiler->getTagRoot());

        $tag = new Tag('div', [], '');
        $compiler->tagRoot = $tag;
        $this->assertSame($tag, $compiler->getTagRoot());
    }

    public function testGetTagName()
    {
        $compiler = new Compiler();
        $this->assertIsArray($compiler->getTagName());
        $this->assertEmpty($compiler->getTagName());

        $compiler->tagName = ['test' => new Tag('div', [], '')];
        $this->assertArrayHasKey('test', $compiler->getTagName());
    }

    public function testCompileRealTime()
    {
        $html = <<<HTML
        <!DOCTYPE html>
            <html lang="vi">
            <head><title>html file</title></head>
            <body>
            <div id="root">
            <span>Test</span>
            </div>
            </body>
        </html>
        HTML;

        $compiler = new Compiler();
        $domDocument = (new IO())->readHTMLRaw($html);
        $tagRoot = $compiler->compileRealTime($domDocument);

        $this->assertInstanceOf(Tag::class, $tagRoot);
        $this->assertEquals('html', $tagRoot->name);
    }

    public function testFree()
    {
        $compiler = new Compiler();
        $compiler->avStatements = ['test'];
        $compiler->avCounter = 1;

        $compiler->free();
        $this->assertEmpty($compiler->avStatements);
        $this->assertEquals(0, $compiler->avCounter);
    }

    public function testFixSingleQuote()
    {
        $compiler = new Compiler();
        $this->assertEquals("O\\'Reilly", $compiler->fixSingleQuote("O'Reilly"));
    }

    public function testCheckTagAlias()
    {
        $compiler = new Compiler();
        $compiler->checkTagAlias('data-yukanoe-id', 'test-id', 1);
        $this->assertArrayHasKey('test-id', $compiler->listAlias);
        $this->assertEquals(1, $compiler->listAlias['test-id']);
    }

    public function testGetTagAlias()
    {
        $compiler = new Compiler();
        $compiler->listAlias = ['test-id' => 1];
        $compiler->tag = [1 => new Tag('div', [], '')];

        $result = $compiler->getTagAlias();
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('test-id', $compiler->tagName);
    }

    public function testRunBuildTool()
    {
        $compiler = new Compiler();
        $domDocument = new \DOMDocument();
        $domDocument->loadXML('<div id="root"><span>Test</span></div>');

        $statements = $compiler->runBuildTool($domDocument->documentElement);
        $this->assertIsArray($statements);
        $this->assertNotEmpty($statements);
    }
}