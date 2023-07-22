# Private HTML Attributes

## HTML Attributes
 - Tag
   + `data-yukanoe-hidden="true/false"`

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

- Hide: `data-yukanoe-hidden="true"`
- Show: `data-yukanoe-hidden="false"`

**index.html**
```html
<div data-yukanoe-id="message" data-yukanoe-hidden="true">Hi Admin!</div>
```
**index.php**

```php
// method 1 (recommended)
$avn['message']->show();

// method 2
$avn['message']->atttribute['data-yukanoe-hidden'] = "false";
```

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

