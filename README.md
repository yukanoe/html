# Yukanoe\HTML PHP library!

A PHP library , Simple, minimal and portable DOM.

**Try**
[demo-01.yukanoe.org](https://github.com/yukanoe/demo-01.yukanoe.org)

**Installation**
```bash
composer require yukanoe/html
```

## Table of Contents
- [Overview](#overview)
- [Quick setup guide](#quick-setup-using-composer-recommended)
- [Basic Usage](#basic-usage)
- [Yukanoe Tag Manager](#yukanoe-tag-manager)
- [Test/Demo](./tests)
- [Examples](./examples)
- [Class Tag](./docs/CLASS-TAG.md)
- [Class TagManager](./docs/CLASS-TAG-MANAGER.md)
- [Private HTML Attributes](./docs/HTML-ATTRIBUTES.md)

...

## Overview

### Removing messy and hard-to-read code

**index.php**
```php
<?php
    // $usename = $_GET['username'] ?? '';
    $username = 'admin' ?? '';
?>
<html>
<head><title>
<?php
    if($username)
      echo "HomePage";
    else
      echo "Hi, {$username}";
?>
</title></head>
<body>
<div>
<?php
    if($usename ?? '')
        echo "{$usename}: Say anything.";
    else
        echo "Say a-ny--thin--g--.";
?>
</div>
</body>
</html>
```

### HTMLxPHP with TagManager
**/html/index.html**
```html
<html>
  <head>
    <title data-yukanoe-id="title">HomePage</title>
  </head>
  <body>
    <div data-yukanoe-id="text">Say a-ny--thin--g--.</div>
  </body>
</html>
```
**index.php**
```php
require '/html/index.php';
$username = 'admin' ?? '';
if($username) {
    $avn['title']->text = "Hi, {$username}";
    $avn['text']->text  = "{$usename}: Say anything.";
}
```

## Quick setup using Composer (Recommended)

```bash
composer require yukanoe/html
```

## Alternative method (Not Recommended)
```
include "Tag.php";
```

## Tag Usage

### Class
```php
use \Yukanoe\HTML\Tag;
```

### Constructor
```php
$myTag = new Tag(String $name, Array $attribute,  String $text);
```

### INPUT (index.php)
```php
<?php
require __DIR__.'/vendor/autoload.php';

use \Yukanoe\HTML\Tag;

$html  = new Tag('html', [], '');
$head  = new Tag('head', [], '');
$title = new Tag('title', [], 'Page Title');
$body  = new Tag('body', [], '');
$div   = new Tag('div', ['class'=>'ruby'], '');
$h1    = new Tag('h1', [], 'Hello World!');
$p     = new Tag('p', [], 'This is a paragraph.');
$html->addChild([$head, $body]);
$head->addChild($title);
$body->addChild($div);
$div->addChild([$h1, $p]);
```

### OUTPUT (browser)
```html
<!DOCTYPE html>
<html>
  <head>
    <title>Page Title</title>
  </head>
  <body>
    <div class="ruby">
      <h1>Hello World!</h1>
      <p>This is a paragraph.</p>
    </div>
  </body>
</html>
```

## Basic Usage

### set attribute

#### public attributes
```php
$myTag->name = "div"
// class attribute
$myTag->attribute['class'] = 'card';
// all attribute
$myTag->attribute = ['class'=>'card'];
// text
$myTag->text = 'string 123456789';
```
#### public methods
```php
$myTag->setName('div');
$myTag->setAttribute(['class'=>'card']);
$myTag->setAttribute('class', 'card');
$myTag->setText('string 123456789');
```
### hide()/show()
```php
$myTag->hide();
$myTag->show();
```

## Link method

### addChild() x 3.1415926535897932384626433
```php
$html->addChild($head);
$html->addChild([$head, $body]);
$html->addChild($head, $body);
```

### Loops
**/examples/example-00.php**
```php
$messages = [
    ["name" => "admin", "text" => "bar"],
    ["name" => "user1", "text" => "foo"],
    ["name" => "admin", "text" => "barbarbar"],
    ["name" => "user1", "text" => "foofoofoo"]
];
$body->addChild($center = new Tag('div'));
foreach ( $messages as $msg ) {
  $center->addChild(new Tag('p', [], "{$msg['name']}: {$msg['text']} "));
}
```

### Clone  ( DEFAULT: deep clone )
**/examples/example-00.php**
```php
$messages = [
    ["name" => "admin", "text" => "bar"],
    ["name" => "user1", "text" => "foo"],
    ["name" => "admin", "text" => "barbarbar"],
    ["name" => "user1", "text" => "foofoofoo"]
];
$body->addChild($center = new Tag('div'));
$msgDiv  = new Tag('div',  ['class'=>'message'], '');
$msgDiv->addChild([
    new Tag('span', ['style'=>' font-weight: bold; '], ''),
    new Tag('span', [], '')
]);
foreach ( $messages as $msg) {
    $newDivMsg = clone $msgDiv;
    $newDivMsg->child[0]->text = $msg['name'];
    $newDivMsg->child[1]->text = $msg['text'];
    $center->addChild($newDivMsg);
}
```

### Conditional Statements
**/examples/example-00.php**
```php
$user ??= '';
if($user == 'admin'){
  $center->attribute['class'] .= ' ruby';
  $center->text = $user;
}
```



## Yukanoe Tag Manager

### Create build tool - build.php

**build.php**
```php
require __DIR__.'/vendor/autoload.php';

use \Yukanoe\HTML\TagManager;

$inputDir   = './html';
$outputDir  = './html';

$tagManager = new TagManager;
$tagManager->autoBuild($inputDir, $outputDir); 

```

### Run build tool

```bash
php build.php
```

### usage

**index.php**
```php
<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/html/index.php';

echo "av: [".count($av)."], avn: ";
foreach ($avn as $key => $value) {
    echo "{$key},";
}



```

## Author
kirishimayuu (kirishimayuu@yukanoe.org)

## License
Yukanoe\HTML is licensed under the MIT License - see the LICENSE file for details.
