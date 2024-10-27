<?php

require __DIR__ . '/bootstrap.php';

use Yukanoe\HTML\Tag;

// html
$html = new Tag('html', [], '');
$head = new Tag('head', [], '');
$title = new Tag('title', [], 'Page Title');
$body = new Tag('body', [], '');
$div = new Tag('div', ['class' => 'ruby'], '');
$h1 = new Tag('h1', [], 'Hello World!');
$p = new Tag('p', [], 'This is a paragraph.');
$html->addChild([$head, $body]);
$head->addChild($title);
$body->addChild($div);
$div->addChild([$h1, $p]);

// Data
$messages = [
    ["name" => "admin", "text" => "bar"],
    ["name" => "user1", "text" => "foo"],
    ["name" => "admin", "text" => "barbarbar"],
    ["name" => "user1", "text" => "foofoofoo"]
];

// Loops
$body->addChild(new Tag('hr'));
$body->addChild(new Tag('h2', [], 'Loops'));
$body->addChild($center = new Tag('div'));
foreach ($messages as $msg) {
    $center->addChild(new Tag('p', [], "{$msg['name']}: {$msg['text']} "));
}


// Clone ( DEFAULT: deep clone )
$body->addChild(new Tag('hr'));
$body->addChild(new Tag('h2', [], 'Clone ( DEFAULT: deep clone )'));
$body->addChild($center = new Tag('div'));
$msgDiv = new Tag('div', ['class' => 'message'], '');
$msgDiv->addChild([
    new Tag('span', ['style' => ' font-weight: bold; '], ''),
    new Tag('span', [], '')
]);
foreach ($messages as $msg) {
    $newDivMsg = clone $msgDiv;
    $newDivMsg->child[0]->text = $msg['name'];
    $newDivMsg->child[1]->text = $msg['text'];
    $center->addChild($newDivMsg);
}


// Conditional Statements
$body->addChild(new Tag('hr'));
$body->addChild(new Tag('h2', [], 'Conditional Statements'));
$body->addChild($center = new Tag('div'));
foreach ($messages as $msg) {
    $newDivMsg = clone $msgDiv;
    $center->addChild($newDivMsg);
    $newDivMsg->child[0]->text = $msg['name'];
    $newDivMsg->child[1]->text = $msg['text'];
    if ($msg['name'] == 'admin')
        $newDivMsg->child[0]->attribute['style'] .= 'color: red;';
    else
        $newDivMsg->child[0]->attribute['style'] .= 'color: blue;';
}
