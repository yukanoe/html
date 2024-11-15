<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Yukanoe\HTML\TagManager\Reducer;
use Yukanoe\HTML\TagManager\Compiler;

class ReducerTest extends TestCase
{
    public function testReduce()
    {
        $inputStatements = [
            "\$av[10] = new Tag('div', ['class'=>'card'], '');",
            "\$av[10]->text = 'UwU';",
            "\$av[11] = new Tag('span', [], '');",
            "\$av[11]->text = 'Hello';"
        ];

        $expectedOutput = [
            "\$av[10] = new Tag('div', ['class'=>'card'], 'UwU');",
            "\$av[11] = new Tag('span', [], 'Hello');"
        ];

        $reducer = new Reducer();
        $output = $reducer->reduce($inputStatements);

        $this->assertEquals($expectedOutput, $output);
    }
}