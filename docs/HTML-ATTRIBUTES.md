# Private HTML Attributes

    What are "data-yukanoe-*" attributes?

 - `data`: [HTML5 custom data attributes prefixed with data-.](https://developer.mozilla.org/en-US/docs/Learn/HTML/Howto/Use_data_attributes)

 - `yukanoe`: namespace yukanoe

## HTML Attributes
 - Tag
   + `data-yukanoe-hidden="any"`
   + `data-yukanoe-restricted="any"`

 - Tag Manager
   + `data-yukanoe-id="string"`
   + `<!-- data-yukanoe-include="filepath.html" -->`

### data-yukanoe-id ( $avn['id'] )
**index.html**
```html
<title data-yukanoe-id="title">HomePage</title>
```
**index.php**
```php
$avn['title']->text = "New Title";
```

### data-yukanoe-hidden (hide/show)

- Hide: default
- Show: isset(`data-yukanoe-hidden`)

**index.html**
```html
<div data-yukanoe-id="message" data-yukanoe-hidden="true">Hi Admin!</div>
```
**index.php**

```php
// method 1 (recommended)
$avn['message']->show();

// method 2
$avn['message']->atttribute['data-yukanoe-hidden'] = "hidden";
```

### data-yukanoe-restricted

    enable/disable htmlspecialchars

- default: disable
- isset(data-yukanoe-restricted) => enable

## Safety Import Guide

### Directory & File Structure
```
id-yukanoe-html
│
└───html
   │   index.html
   │   head.html
   │   body-header.html
   │   body-footer.html

```

### Detail

- 1. **index.php**
```html
<!doctype html>
<html data-theme="dark">
<!-- data-yukanoe-include="head.html" -->
<body>
  <!-- yukanoe-include="body-header.html" -->
  <div class="bg-base-200 py-6 px-4">hi!</div>
  <!-- yukanoe-include="body-footer.html" -->
</body>
</html>

```

- 2. **head.html**
```html
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../dist/output.css" rel="stylesheet">
  <title data-yukanoe-id="title"> nothing xxx </title>
</head>
```

- 3. **body-header.html**
```html
<nav>...</nav>
```

- 4. **body-footer.html**
```html
<footer>...</footer>
```

