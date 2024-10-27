# Yukanoe\HTML PHP library!

A PHP library , Simple, minimal and portable DOM.

**Try:** [demo-01.yukanoe.org](https://github.com/yukanoe/demo-01.yukanoe.org)

## 1 Installation
```bash
composer require yukanoe/html
```

## 2 Table of Contents

- [Class `Yukanoe\HTML\Tag`](./docs/CLASS-TAG.md)
- [Private HTML Attributes](./docs/HTML-ATTRIBUTES.md)
- (optional) [Overview](./overview.md)
- (optional) [Test/Demo Directory](./tests)
- (optional) [Examples Directory](./examples)
- (optional) [Class TagManager](./docs/CLASS-TAG-MANAGER.md)


## 3 Get started

### Create Tag
```php
use Yukanoe\HTML\Tag;

$myTag = new Tag(string $name='', array $attribute=[],  string $text='');
```

### INPUT (Server: index.php)
```php
<?php
# index.php
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

## 4 Basic Usage

### 4.1 set attribute

#### 4.1.1 public attributes
```php
$myTag->name = "div"
// class attribute
$myTag->attribute['class'] = 'card';
// all attribute
$myTag->attribute = ['class'=>'card'];
// text
$myTag->text = 'string 123456789';
```
#### 4.1.2 public methods
```php
$myTag->setName('div');
$myTag->setAttribute(['class'=>'card']);
$myTag->setAttribute('class', 'card');
$myTag->setText('string 123456789');
```
### 4.2 hide/show: hide/show a Tag
```php
$myTag->hide();
$myTag->show();
```

### 4.3 restrict: htmlspecialchars(content)
```php
$myTag->restrict(string $scope='restricted');
$myTag->restrict(string $scope='none');
```

### 4.4 addChild 


```php
# optional 1: recommended
$html->addChild([$head, $body]);

# optional 2
$html->addChild($head, $body);

# optional 3
$html->addChild($head)->addChild($body);

```

### Loops

[example-00.php](./examples/example-00.php)

- data
```php
$messages = [
    ["name" => "admin", "text" => "bar"],
    ["name" => "user1", "text" => "foo"],
    ["name" => "admin", "text" => "barbarbar"],
    ["name" => "user1", "text" => "foofoofoo"]
];
$body->addChild($center = new Tag('div'));
foreach ($messages as $msg) {
  $center->addChild(new Tag('p', [], "{$msg['name']}: {$msg['text']} "));
}
```

- Clone  (NOTICE: `Tag` is `deep clone`)

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

- Conditional Statements
```php
$user ??= '';
if($user == 'admin'){
  $center->attribute['class'] .= ' ruby';
  $center->text = $user;
}
```

## DOMDocument

[HTML5 Support (PHP 8.4)](https://www.zend.com/blog/php-8-4)

- While HTML5 has been around for a very long time now,
the DOM parser used by the PHP engine has lingered behind,
only supporting HTML 4.01 features.

- The PHP 8.4 release rectifies that situation with comprehensive support for HTML5,
via adoption of a more capable HTML5 parsing library,
and new opt-in DOM classes that exist in a new PHP namespace
to allow differentiation from the existing XML-oriented DOM classes.

### PHP 8.3 and below - default character set ISO-8859-1
DOMDocument::loadHTML will treat your string as being in ISO-8859-1 (the HTTP/1.1 default character set) unless you tell it otherwise. This results in UTF-8 strings being interpreted incorrectly.

```html
- <meta charset="utf-8" />
- <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
is required
```

### PHP 8.4 - DOM HTML5 parsing and serialization
- https://wiki.php.net/rfc/domdocument_html5_parser
- https://wiki.php.net/todo/php84 - Jul 16 2024   Feature freeze
How PHP 8.4 Will Be Improved to Provide Better Support to Parse and Process HTML5 Pages and Files


## Author
kirishimayuu (kirishimayuu@yukanoe.org)


## License
Yukanoe\HTML is licensed under the MIT License - see the LICENSE file for details.
