# Yukanoe\HTML\Tag


## class

```php
class Tag {

    # Configurable
    Tag::$autoFlush     = true;
    Tag::$documentType  = '<!DOCTYPE html>'; 
    Tag::$singletonTags = ['img', 'meta', 'input', 'link', 'br', 'source'];
    
    # Attributes:
    public string $name;      //'html', 'div', 'a', 'br',..
    public array  $attribute; // ['class'=>'ruby', 'href'=>'/index.php']
    public string $text;      // 'hello world.'
    public array  $child;     // [Tag, Tag,..]
    public Tag    $parent;    // Tag
    
    # Methods:
    ## setter/getter: Name, Attribute, Text, Parent
    public function setName(string $name): void;
    public function getName(): string;
    public function setAttribute(array $name): void;
    public function getAttribute(): array;
    public function setText(string $name): void;
    public function getText(): string;
    public function setParent(Tag $parent): void;
    public function getParent(): Tag;
    
    ## list return self methods
    public function appendChild(Tag $tag): self;
    public function prependChild(Tag $tag): self;
    public function insertAfter(Tag $tag): self;
    public function insertBefore(Tag $tag): self;
    public function removeLastChild(): self;
    public function removeFirstChild(): self;
    public function removeChild(Tag $tag): self;
    public function removeChildIndex(int $index): self;
    public function hide(): self;
    public function show(): self;
    public function empty(): self;
    public function destroy(): self;

    ## list void methods
    public function flush(): void;    //flush current state
    public function newTrace(): void; //trace child[]
    
    ## Return OtherTag
    public function getRoot(): Tag;
    public function getAncestorByName(String $search): Tag;
    
    ## Return HTML String
    public function get(): string; //$html
    
    ## addChild
    public function addChild(array $tags): self;
    public function addChild(Tag $tag): self;
    public function addChild(...Tag $tag): self;

}
```

## public function `addChild()`

- create tags
```php
$div = new Tag('div');
$a   = new Tag('a');
$p   = new Tag('p');
```
- `$div` addChild `$a`, `$p`
```php
# optional 1
$div->addChild([$a, $p]);

# optional 2
$div->addChild($a, $p);

# optional 3
$div->addChild($a)->addChild($p);

```

```html
<div>
    <a></a>
    <p></p>
</div>
```

- `$div` addChild `$p`, `$p` addChild `$a`

```php
# optional 1
$div->addChild($p);
$p->addChild($a);

# optional 2
$p->addChild($a);
$div->addChild($p)

# optional 3
$div->addChild(
    $p->addChild($a)
);

```

```html
<div>
    <p>
        <a></a>
    </p>
</div>
```