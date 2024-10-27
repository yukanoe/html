# use \Yukanoe\HTML\Tag;

## Tag 

### Class Attributes (public):
```php
    $name      //'html', 'div', 'a', 'br',..
    $attribute // ['class'=>'ruby', 'href'=>'/index.php']
    $text      // 'hello world.'
    $child     // [Tag, Tag,..]
    $parent    // Tag
```
### Class Methods:
**setter/getter: Name, Attribute, Text, Parent**
```php
setName(String $name)
getName()
//...
```
**addChild***
```php
addChild(Tag $a)
addChild(Tag $a, Tag $b)
addChild([Tag $a, Tag $b])
```
**All**
```php
# list return self methods
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

# list void methods
flush(): void    //flush current state
newTrace(): void //trace child[]

# Return OtherTag
getRoot(): Tag
getAncestorByName(String $search): Tag

# Return HTML String
get(): string //$html

```

### Configurable

```php
    Tag::$autoFlush     = true;
    Tag::$documentType  = '<!DOCTYPE html>'; 
    Tag::$singletonTags = ['img', 'meta', 'input', 'link', 'br', 'source'];
```

