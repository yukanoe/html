
# TagManager;

## 3 HTML->PHP with TagManager

**Directory: [/examples/project](../examples)**

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


## Yukanoe Tag Manager

### Auto Build
**build.php**
```php
<?php
require 'vendor/autoload.php';

use \Yukanoe\HTML\TagManager;

$TagManager = new TagManager;
$TagManager->autoBuild('./html', './html-php'); //input, output

```
### Building

```bash

php build.php

```

### Auto Build with VarName
```php

require 'vendor/autoload.php';

use \Yukanoe\HTML\TagManager;

$TagManager = new TagManager;
/**
 * 
 * $AV[Index]->newTrace();
 * $AVN['data-yukanoe-id']->newTrace();
 * 
 */
$TagManager->configureVarName('html', 'yukanoeid');
$TagManager->autoBuild('./html', './html-php');

```

## Resources

### specific file
```php
$TagManager->read('index.html')->build()->view('none.php');
```

### autoBuild
```php
$TagManager = new \Yukanoe\HTML\TagManager;
$TagManager->autoBuild('./');
```

### HTML Testing With eval()
```php
eval(
  $TagManager->read('index.html')->build()->get();
);
```



