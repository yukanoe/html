# Yukanoe\HTML PHP library!

A PHP library , Simple, minimal and portable DOM.

**Try:** [demo-01.yukanoe.org](https://github.com/yukanoe/demo-01.yukanoe.org)

## 1 Installation
```bash
composer require yukanoe/html
```

## 2 Table of Contents
- [Overview](./overview.md)
- [Quick setup guide](#3-html-php-with-tagmanager)
- [Tag Basic Usage](#4-tag-usage)
- [Test/Demo Directory](./tests)
- [Examples Directory](./examples)
- [Class Tag](./docs/CLASS-TAG.md)
- [Class TagManager](./docs/CLASS-TAG-MANAGER.md)
- [Private HTML Attributes](./docs/HTML-ATTRIBUTES.md)

...


## 3 HTML->PHP with TagManager

**Directory: [/examples/project](./examples/project)**

### 3.1 Directory & File Structure 
```
project/
│   build.php    
│   index.php      
│
└───template-html/
│   │   index.html
│   │   ...
│   
└───template-php/
```

### 3.2 Create build tool - build.php

**build.php**
```php
require __DIR__.'/vendor/autoload.php';

use \Yukanoe\HTML\TagManager;

$inputDir   = './template-html';
$outputDir  = './template-php';

$tagManager = new TagManager;
$tagManager->autoBuild($inputDir, $outputDir); 

```
### 3.3 Create html files

**project/html/index.html**
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

### 3.4 Create php files
**project/index.php**
```php
<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/template-php/index.php';

$username = $_GET['username'] ?? '';
if($username) {
    $avn['title']->text = "Hi, {$username}";
    $avn['text']->text  = "{$username}: Say anything.";
}
```

### 3.5 Run build tool & Built-in Web server
```bash
cd project/
composer require yukanoe/html
php build.php
php -S localhost:8080 index.php
```
## 3.6 Open
- http://localhost:8080
- http://localhost:8080/?username=admin


## 4 Tag Usage

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

## DOMDocument
### php 8.3 and below - default character set ISO-8859-1
DOMDocument::loadHTML will treat your string as being in ISO-8859-1 (the HTTP/1.1 default character set) unless you tell it otherwise. This results in UTF-8 strings being interpreted incorrectly.

- <meta charset="utf-8" />
- <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
is required

### php 8.4 - DOM HTML5 parsing and serialization
https://wiki.php.net/rfc/domdocument_html5_parser
https://wiki.php.net/todo/php84 - Jul 16 2024   Feature freeze
How PHP 8.4 Will Be Improved to Provide Better Support to Parse and Process HTML5 Pages and Files


## Author
kirishimayuu (kirishimayuu@yukanoe.org)

## License
Yukanoe\HTML is licensed under the MIT License - see the LICENSE file for details.
