# Private HTML Attributes

## 1. What are "data-yukanoe-*" attributes?

 - `data`: [HTML5 custom data attributes prefixed with data-.](https://developer.mozilla.org/en-US/docs/Learn/HTML/Howto/Use_data_attributes)

 - `yukanoe`: namespace yukanoe

## 2. list HTML Attributes & Comment

- Include files other:
```html
<!-- data-yukanoe-include="filepath.html" -->
```
- Control HTML Tag
```html
<div
        data-yukanoe-id="my-div" 
        data-yukanoe-hidden="hidden" 
        data-yukanoe-restricted="restricted"
>

   My Text 132456

</div>
```
| name                      | value                |       
|---------------------------|----------------------|
| `data-yukanoe-id`         | `string-id`          |
| `data-yukanoe-hidden`     | `hidden`, `any`      |
| `data-yukanoe-restricted` | `restricted`, `any`  |



### 2.1 ID `data-yukanoe-id` ($avn['id'])
**index.html**
```html
<title data-yukanoe-id="title">HomePage</title>
```
**index.php**
```php
$avn['title']->text = "New Title";
```

### 2.2 HIDDEN `data-yukanoe-hidden` (hide/show)
    hide/show tag

- Hide: default
- Show: isset(`data-yukanoe-hidden`)


```html
<div data-yukanoe-id="message" data-yukanoe-hidden="hidden">
    Hi Admin!
</div>
```

```php
// optional 1 (recommended)
$avn['message']->show();
$avn['message']->hide();

// optional 2
$avn['message']->atttribute['data-yukanoe-hidden'] = "hidden";
unset($avn['message']->atttribute['data-yukanoe-hidden'])
```

### 2.3 RESTRICTED `data-yukanoe-restricted`

    enable/disable htmlspecialchars

- default: disabled
- `isset(data-yukanoe-restricted)`: enabled
```html
<div data-yukanoe-id="message" data-yukanoe-restricted="restricted">
    Hi Admin!
</div>
```

```php
// optional 1 (recommended)
$avn['message']->unrestrict();
$avn['message']->restrict();

// optional 2
$avn['message']->atttribute['data-yukanoe-restricted'] = "restricted";
unset($avn['message']->atttribute['data-yukanoe-restricted'])
```

## 3. Safety Import Guide

### 3.1 `__dir__` The directory of the file
- examples:
- lv1: `./`      => `__dir__` 
- lv2: `../`     => `__dir__/../`
- lvn: `../../`  => `__dir__/../../`

```html

<!-- data-yukanoe-include="__dir__/components/alert.html" -->

<!-- data-yukanoe-include="__dir__/./components/alert.html" -->

<!-- data-yukanoe-include="__dir__/../components/alert.html" -->

```


### 3.2 Directory & File Structure
```
id-yukanoe-html
│
└───html
   │   index.html
   │   head.html
   │   body-header.html
   │   body-footer.html

```

### 3.2 Detail

1. **index.php**

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

2. **head.html**
```html
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../dist/output.css" rel="stylesheet">
  <title data-yukanoe-id="title"> nothing xxx </title>
</head>
```

3. **body-header.html**
```html
<nav>...</nav>
```

4. **body-footer.html**
```html
<footer>...</footer>
```
