<?php

namespace Tests;

include(__DIR__."/../src/Tag.php");

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use Yukanoe\HTML\Tag;

class TagTest extends TestCase
{

    public function testPHPVersion()
    {
        $tag = new Tag('div', ['class' => 'default'], "hello");
        $this->assertTrue( is_object($tag) );
    }

    public function testNewTag()
    {
        $tag = new Tag('div', ['class' => 'default'], "hello");
        $expectedResult = '<div class="default"> hello</div>';
        $this->assertEquals($tag->get(), $expectedResult);
    }

    public static function providerTestConstruct(): array
    {
        return [
            [
                new Tag('div', ['class' => 'default'], "hello"),
                '<div class="default"> hello</div>'
            ],
            [
                new Tag('div', [], "hello"),
                '<div> hello</div>'
            ]
        ];
    }

    #[DataProvider('providerTestConstruct')]
    public function testConstruct(Tag $tag, string $expectedResult): void
    {
        $this->assertEquals($tag->get(), $expectedResult);
    }
}
