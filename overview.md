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
