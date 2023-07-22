<?php
require __DIR__.'/vendor/autoload.php';

use \Yukanoe\HTML\Tag;

$html = (new Tag('html', [], ''))->addChild([

    $head = new Tag('head', [], '')->addChild(
      new Tag('title', [], 'Page Title')
    ),

    $body = new Tag('body', [], '')->addChild(
      (new Tag('div', ['class'=>'ruby'], ''))->addChild([
        new Tag('h1', [], 'Hello World!'),
        new Tag('p',  [], 'This is a paragraph.')
      ])
    )

]);