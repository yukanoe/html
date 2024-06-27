<?php
require __DIR__ . "/bootstrap.php";

use \Yukanoe\HTML\Tag;

Tag::$autoFlush = true;

function Tag($name, $attribute, $text)
{
    return new Tag($name, $attribute, $text);
}

$html = Tag('html', [], '')->addChild([

    $head = Tag('head', [], '')->addChild(
       $title = Tag('title', [], 'Page Title')
    ),

    $body =  Tag('body', [], '')->addChild(
        $ruby = Tag('div', ['class'=>'ruby'], '')->addChild([
            $pageTitle   = Tag('h1', [], 'Hello World!'),
            $pageContent = Tag('p',  [], 'This is a paragraph.')
        ])
    )

]);

$title->text = "Wiz Wiz Wiz";
$pageContent->text = "Wiz Wizz Wizzz";
