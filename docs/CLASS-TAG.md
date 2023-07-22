# use \Yukanoe\HTML\Tag;

## Tag 

### Class Attributes:
```php
    $name      //'html', 'div', 'a', 'br',..
    $attribute // ['class'=>'ruby', 'href'=>'/index.php']
    $text:     // 'hello world.'
    $child:    // [Tag, Tag,..]
    $parent:   // Tag
```
### Class Methods:
**set/get**
```php
setName(String $name)
getName()
//attribute, text, parent
```
**addChild***
```php
addChild(Tag)
addChild([Tag, Tag])
addChild(Tag, Tag)
```
**all**
```php
//return $this;
appendChild($tag) 
prependChild($tag)
insertAfter($tag)
insertBefore($tag)
removeLastChild()
removeFirstChild()
removeChild($tag)
removeChildIndex(Int $index)
hide()
show()
empty()
destroy()

// Return;
flush()    //flush current state
newTrace() //trace child[]

// Return OtherTag
getRoot() : Tag
getAncestorByName(String $search) : Tag

// Return HTML String
get() : String $html

```

### Configurable

```php
    Tag::$autoFlush     = true;
    Tag::$documentType  = '<!DOCTYPE html>'; 
    Tag::$singletonTags = ['img', 'meta', 'input', 'link', 'br', 'source'];
```

