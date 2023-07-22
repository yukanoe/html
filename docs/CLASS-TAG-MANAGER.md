# use \Yukanoe\HTML\TagManager;

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



