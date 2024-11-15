<?php

use PHPUnit\Framework\TestCase;
use Yukanoe\HTML\Tag;
use Yukanoe\HTML\TagManager;
use Yukanoe\HTML\TagManager\IO;
use Yukanoe\HTML\TagManager\Compiler;
use Yukanoe\HTML\TagManager\Reducer;

class TagManagerTest extends TestCase
{
    private TagManager $tagManager;

    protected function setUp(): void
    {
        $this->tagManager = new TagManager();
    }

    public function testRead()
    {
        $this->tagManager->read('test.html');
        $this->assertInstanceOf(TagManager::class, $this->tagManager);
    }

    public function testReadRaw()
    {
        $this->tagManager->readRaw('raw html');
        $this->assertInstanceOf(TagManager::class, $this->tagManager);
    }

    public function testBuild()
    {
        $this->tagManager->read('test.html')->build();
        $this->assertInstanceOf(TagManager::class, $this->tagManager);
    }

    public function testSaveGet()
    {
        $this->tagManager->read('test.html')->build()->save(__DIR__ . '/output/render.php');
        $this->assertInstanceOf(TagManager::class, $this->tagManager);
        $result = $this->tagManager->get(__DIR__ . '/output/render.php');
        $this->assertIsString($result);
    }

    public function testView()
    {
        $this->tagManager->read('test.html')->build()->view();
        $this->assertInstanceOf(TagManager::class, $this->tagManager);
    }

    public function testConfigureVarName()
    {
        $this->tagManager->configureVarName('index', 'alias');
        $this->assertEquals('index', Compiler::$regVarName);
        $this->assertEquals('alias', Compiler::$aliVarName);
    }

    public function testReadRealTimeRaw()
    {
        $tag = $this->tagManager->readRealTimeRaw('raw html');
        $this->assertInstanceOf(Tag::class, $tag);
    }

    public function testReadRealTime()
    {
        $tag = $this->tagManager->readRealTime(__DIR__ . '/resource/test.html');
        $this->assertInstanceOf(Tag::class, $tag);
    }

    public function testReadRealTimeNonXHTML()
    {
        $tag = $this->tagManager->readRealTime(__DIR__ . '/resource/test-nonXHTML.html');
        $this->assertInstanceOf(Tag::class, $tag);
    }

}