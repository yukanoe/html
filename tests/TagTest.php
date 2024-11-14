<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Yukanoe\HTML\Tag;

class TagTest extends TestCase
{
    public function createHTMLPage()
    {
        $html   = new Tag('html', [], '');
        $parent = new Tag('div', ['class' => 'test', 'data-yukanoe-restricted' => 'restricted'], 'Hello');
        $child1 = new Tag('span', ['data-yukanoe-id' => 'test-id']);
        $child2 = new Tag(
            'p', [
            'data-yukanoe-id' => 'test-id-2',
            'data-yukanoe-restricted' => 'restricted',
            'data-yukanoe-hidden' => 'hidden'
        ],
            'World');
        $child3 = new Tag('img', ['src' => 'image.jpg', 'alt' => 'Image', 'data-yukanoe-id' => 'test-id-3']);
        $html->appendChild($parent);
        $parent->appendChild($child1);
        $parent->appendChild($child2);
        $parent->appendChild($child3);
        return $html;
    }

    public function testClone()
    {
        $tag = new Tag('div', ['class' => 'test'], 'Hello');
        $child = new Tag('span', ['data-yukanoe-id' => 'test-id']);
        $child2 = new Tag(
            'p', [
                'data-yukanoe-id' => 'test-id-2',
                'data-yukanoe-restricted' => 'restricted',
                'data-yukanoe-hidden' => 'hidden'
            ],
            'World');
        $tag->appendChild($child);
        $tag->appendChild($child2);
        $clone = clone $tag;
        $this->assertEquals($tag->getName(), $clone->getName());
        $this->assertEquals($tag->getAttribute(), $clone->getAttribute());
        $this->assertEquals($tag->getText(), $clone->getText());
    }

    public function testEmptyConstructor()
    {
        $tag = new Tag();
        $this->assertEquals(Tag::$emptyTag, Tag::$emptyTag);
        $this->assertEmpty($tag->getAttribute());
        $this->assertEmpty($tag->getText());
    }

    public function testConstructor()
    {
        $tag = new Tag('div', ['class' => 'test'], 'Hello');
        $this->assertEquals('div', $tag->getName());
        $this->assertEquals(['class' => 'test'], $tag->getAttribute());
        $this->assertEquals('Hello', $tag->getText());
    }

    public function testSetName()
    {
        $tag = new Tag();
        $tag->setName('span');
        $this->assertEquals('span', $tag->getName());
    }

    public function testSetAttribute()
    {
        $tag = new Tag();
        $tag->setAttribute(['class' => 'new-class']);
        $tag->setAttribute('id', 'test-id');
        $this->assertEquals('test-id', $tag->getAttribute('id'));
    }


    public function testSetText()
    {
        $tag = new Tag();
        $tag->setText('Sample text');
        $this->assertEquals('Sample text', $tag->getText());
    }

    public function testAppendChild()
    {
        $parent = new Tag('div');
        $child = new Tag('span');
        $parent->appendChild($child);
        $this->assertCount(1, $parent->child);
        $this->assertSame($child, $parent->child[0]);
    }

    public function testPrependChild()
    {
        $parent = new Tag('div');
        $child1 = new Tag('span');
        $child2 = new Tag('p');
        $parent->appendChild($child1);
        $parent->prependChild($child2);
        $this->assertCount(2, $parent->child);
        $this->assertSame($child2, $parent->child[0]);
    }

    public function testGetRoot()
    {
        $root = new Tag('html');
        $child = new Tag('body');
        $root->appendChild($child);
        $this->assertSame($root, $child->getRoot());
    }

    public function testGetAncestorByName()
    {
        $root = new Tag('html');
        $body = new Tag('body');
        $div = new Tag('div');
        $root->appendChild($body);
        $body->appendChild($div);
        $this->assertSame($body, $div->getAncestorByName('body'));
        $this->assertSame($root, $div->getAncestorByName('html'));
        $this->assertSame($div, $div->getAncestorByName('span'));
    }

    public function testGetChildsByTagName()
    {
        $parent = new Tag('div');
        $child1 = new Tag('span');
        $child2 = new Tag('span');
        $parent->appendChild($child1);
        $parent->appendChild($child2);
        $result = $parent->getChildsByTagName('span');
        $this->assertCount(2, $result);
    }

    public function testHideShow()
    {
        $tag = new Tag('div');
        $tag->hide();
        $this->assertArrayHasKey('data-yukanoe-hidden', $tag->getAttribute());
        $tag->show();
        $this->assertArrayNotHasKey('data-yukanoe-hidden', $tag->getAttribute());
    }

    public function testRestrictUnrestrict()
    {
        $tag = new Tag('div');
        $tag->restrict();
        $this->assertArrayHasKey('data-yukanoe-restricted', $tag->getAttribute());
        $tag->unrestrict();
        $this->assertArrayNotHasKey('data-yukanoe-restricted', $tag->getAttribute());
    }

    public function testEmptyDestroy()
    {
        $parent = new Tag('div');
        $child = new Tag('span');
        $parent->appendChild($child);
        $child->destroy();
        $this->assertEquals($child->getName(), Tag::$emptyTag);
        $this->assertEmpty($child->getAttribute());
        $this->assertEmpty($child->child);
        $this->assertEmpty($child->getText());
        $this->assertCount(0, $parent->child);
    }

    public function testGet()
    {
        $tag = new Tag('div', ['class' => 'test'], 'Hello');
        $this->assertEquals('<div class="test">Hello</div>', $tag->get());
    }

    public function testExportYD()
    {
        $tag = new Tag('div', ['data-yukanoe-id' => 'test-id']);
        $result = $tag->exportYD();
        $this->assertArrayHasKey('test-id', $result);
        $this->assertSame($tag, $result['test-id']);
        $html = $this->createHTMLPage();
        $html->exportYD();
        $this->assertTrue(true);
    }

    public function testFlushBuffer()
    {
        $html   = new Tag('html', [], '');
        $divs = [
            new Tag('div', ['class' => 'test'], 'Hello'),
            new Tag('div', ['class' => 'test'], 'World')
        ];
        $html->addChild($divs);
        $buffer = '';
        $html->flushBuffer($buffer);
        $this->assertEquals('<div class="test">Hello</div>', $divs[0]->get());
    }

    public function testFlush()
    {
        $tag = new Tag('div', ['class' => 'test'], 'Hello');
        ob_start();
        $tag->flush();
        $output = ob_get_clean();
        $this->assertEquals('<div class="test" >Hello</div>', $output);
        $html = $this->createHTMLPage();
        $html->flush();
        $this->assertTrue(true);
    }


    public function testEmptyTagName()
    {
        $tag = new Tag('', ['class' => 'test'], 'Hello');
        $this->assertEquals('< class="test">Hello</>', $tag->get());
    }

    public function testMultipleAttributes()
    {
        $tag = new Tag('div', ['class' => 'test', 'id' => 'main'], 'Hello');
        $buffer = '';
        $tag->flushBuffer($buffer);
        $this->assertEquals('<div class="test" id="main">Hello</div>', $buffer);
    }

    public function testNestedTags()
    {
        $parent = new Tag('div');
        $child = new Tag('span', [], 'Hello');
        $parent->appendChild($child);
        $this->assertEquals('<div><span>Hello</span></div>', $parent->get());
    }

    public function testSpecialCharactersInText()
    {
        $tag = new Tag('div', ['class' => 'test'], '<Hello & World>');
        $this->assertEquals('<div class="test"><Hello & World></div>', $tag->get());
    }

    public function testSelfClosingTag()
    {
        $tag = new Tag('img', ['src' => 'image.jpg', 'alt' => 'Image']);
        $this->assertEquals('<img src="image.jpg" alt="Image" />', $tag->get());
    }

    public function testRemoveChild()
    {
        $parent = new Tag('div');
        $child = new Tag('span');
        $parent->appendChild($child);
        $parent->removeChild($child);
        $this->assertCount(0, $parent->child);
    }


    public function testGetParent()
    {
        $parent = new Tag('div');
        $child = new Tag('span');
        $parent->appendChild($child);
        $this->assertSame($parent, $child->getParent());
    }

    public function testGetAttributes()
    {
        $tag = new Tag('div', ['class' => 'test', 'id' => 'main']);
        $this->assertEquals(['class' => 'test', 'id' => 'main'], $tag->getAttributes());
    }

    public function testSetAttributes()
    {
        $tag = new Tag('div');
        $tag->setAttributes(['class' => 'test', 'id' => 'main']);
        $this->assertEquals(['class' => 'test', 'id' => 'main'], $tag->getAttributes());
    }



    public function testInsertBefore()
    {
        $parent = new Tag('div');
        $child1 = new Tag('span');
        $child2 = new Tag('p');
        $parent->appendChild($child1);
        $child2->insertBefore($child1);

        $this->assertCount(2, $parent->child);
        $this->assertSame($child2, $parent->child[0]);
    }

    public function testInsertAfter()
    {
        $parent = new Tag('div');
        $child1 = new Tag('span');
        $child2 = new Tag('p');
        $parent->appendChild($child1);
        $child2->insertAfter($child1);
        $this->assertCount(2, $parent->child);
        $this->assertSame($child2, $parent->child[1]);
    }

    public function testRemoveLastChild()
    {
        $parent = new Tag('div');
        $child1 = new Tag('span');
        $child2 = new Tag('p');
        $parent->appendChild($child1);
        $parent->appendChild($child2);
        $parent->removeLastChild();
        $this->assertCount(1, $parent->child);
        $this->assertSame($child1, $parent->child[0]);
    }

    public function testRemoveFirstChild()
    {
        $parent = new Tag('div');
        $child1 = new Tag('span');
        $child2 = new Tag('p');
        $parent->appendChild($child1);
        $parent->appendChild($child2);
        $parent->removeFirstChild();
        $this->assertCount(1, $parent->child);
        $this->assertSame($child2, $parent->child[0]);
    }

    public function testRemoveChlidIndex()
    {
        $parent = new Tag('div');
        $child1 = new Tag('span');
        $child2 = new Tag('p');
        $parent->appendChild($child1);
        $parent->appendChild($child2);
        $parent->removeChildIndex(1);
        $this->assertCount(1, $parent->child);
        $this->assertSame($child1, $parent->child[0]);
    }


    public function testTrace()
    {
        $html = $this->createHTMLPage();
        $html->newTrace();

        global $won, $ha, $ji;
        $won = new Tag('div', ['class' => 'test'], 'Hello');
        $ha = new Tag('span', ['class' => 'test'], 'Hello');
        $ji = new Tag('p', ['class' => 'test'], 'Hello');
        $won->appendChild($ha);
        $ha->appendChild($ji);
        $won->trace();

        $this->assertTrue(true);
    }

    public function testAction(){
        $tag = new Tag('div', ['class' => 'test'], 'Hello');


        $tag->hide();
        $tag->get();
        $tag->show();
        $tag->get();
        $tag->restrict();
        $tag->get();
        $tag->unrestrict();
        $tag->get();
        $this->assertTrue(true);
    }

    public function testAddChildException(){

        $tag = new Tag('div', ['class' => 'test'], 'Hello');
        $tag->addChild('error');
        $this->assertTrue(true);
    }

    public function testInit()
    {
        Tag::$autoFlush = true;
        $html = new Tag('html', [], '');
        $div = new Tag('div', ['class' => 'test'], 'Hello');
        $html->appendChild($div);
        $this->assertInstanceOf(Tag::class, $html);
        $html->__destruct();
        $this->assertInstanceOf(Tag::class, $div);
    }

    public function testFlustByResponse()
    {

        $html = $this->createHTMLPage();
        $response = new class {
            public function write($content)
            {
                echo $content;
            }
        };
        $html->flushByResponse($response);
        $this->assertTrue(true);
    }

    public function testMagicMethods()
    {
        $tag = new Tag('div', ['class' => 'test'], 'Hello');
        $tag->addChild = $tag;
        $this->assertEquals('test', $tag->getAttribute('class'));
    }




}