<?php

namespace Yukanoe\HTML;

/**
 * Class Tag
 * Represents an HTML tag with attributes, text content, and child tags.
 */
class Tag
{
    public string $name = '';
    public string $text = '';
    public array $attribute = [];
    public ?Tag $parent = null;
    public array $child = [];

    // Configurable
    public static bool $autoFlush = true;
    public static string $documentType = '<!DOCTYPE html>';
    public static array $singletonTags = ['img', 'meta', 'input', 'link', 'br', 'source', 'hr', 'area', 'source', 'track'];

    // Non-configurable
    public static array $singletonAllTags = ['area', 'base', 'basefont', 'bgsound', 'br', 'col', 'command', 'embed', 'frame', 'hr', 'image', 'img', 'input', 'isindex', 'keygen', 'link', 'menuitem', 'meta', 'nextid', 'param', 'source', 'track', 'wbr'];
    public static int $traceCounter = 0;
    public static string $emptyTag = 'yukanoe-empty';

    /**
     * Tag constructor.
     * @param string $name
     * @param array $attribute
     * @param string $text
     */
    public function __construct(string $name = '', array $attribute = [], string $text = '')
    {
        $this->name = $name;
        $this->attribute = $attribute;
        $this->text = $text;
    }

    /**
     * Destructor to auto-flush if enabled.
     */
    public function __destruct()
    {
        if (self::$autoFlush && $this->name == 'html') {
            $this->flush();
        }
    }

    /**
     * Clone method to deep clone child tags.
     */
    public function __clone()
    {
        foreach ($this->child as &$value) {
            $value = clone $value;
        }
    }

    /**
     * Magic set method to add child tags.
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        if ($key == 'addChild') {
            $this->addChild($value);
        }
    }

    // Setters and Getters

    public function setName(string $name): Tag
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setAttribute(...$vAtt): Tag
    {
        if (is_array($vAtt[0])) {
            $this->attribute = $vAtt[0];
            return $this;
        }
        if (isset($vAtt[1]) && is_string($vAtt[0]) && is_string($vAtt[1])) {
            $this->attribute[$vAtt[0]] = $vAtt[1];
        }
        return $this;
    }

    public function getAttribute(?string $name=null): array|string
    {
        if($name === null) {
            return $this->attribute;
        }
        return $this->attribute[$name] ?? '';
    }

    public function setAttributes(array $attributes): Tag
    {
        $this->attribute = $attributes;
        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attribute;
    }

    public function setText(string $text): Tag
    {
        $this->text = $text;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setParent(?Tag $v): Tag
    {
        $this->parent = $v;
        return $this;
    }

    public function getParent(): ?Tag
    {
        return $this->parent;
    }

    // Child manipulation methods

    public function appendChild(Tag $tag): Tag
    {
        $tag->setParent($this);
        $this->child[] = $tag;
        return $this;
    }

    public function prependChild(Tag $tag): Tag
    {
        $tag->setParent($this);
        array_unshift($this->child, $tag);
        return $this;
    }

    public function getRoot(): Tag
    {
        $find = $this;
        while ($find->parent) {
            $find = $find->parent;
        }
        return $find;
    }

    public function getAncestorByName(string $search): Tag
    {
        $find = $this;
        while ($find->parent) {
            $find = $find->parent;
            if ($find->name == $search) {
                return $find;
            }
        }
        return $this;
    }

    public function getChildsByTagName(string $name, array &$tags = []): array
    {
        if ($this->name == $name) {
            $tags[] = $this;
        }
        foreach ($this->child as &$value) {
            $value->getChildsByTagName($name, $tags);
        }
        return $tags;
    }

    /**
     *
     * Add a child tag or an array of child tags.
     * - addChild(Tag1, Tag2, Tag3,...)
     * - addChild([Tag1, Tag2, Tag3,...])
     * - addChild([[Tag1, Tag2, Tag3], [Tag4, Tag5, Tag6], Tag7,...])
     *
     * @param ...$tags
     * @return $this
     */
    public function addChild(...$tags): Tag
    {
        foreach ($tags as $element) {
            if (is_array($element)) {
                foreach ($element as $value) {
                    $this->addChild($value);
                }
            } elseif ($element instanceof Tag) {
                $this->appendChild($element);
            } else {
                $this->addChildException(new \Exception('addChild(non-\Yukanoe\HTML\Tag)'));
            }
        }
        return $this;
    }

    public function addChildException(\Exception $e): void
    {
        echo "<br /> addChild(Tag)";
        echo "<br /> addChild([Tag1, Tag2, Tag3,...])";
        echo "<br /> addChild([[Tag1, Tag2, Tag3], [Tag4, Tag5, Tag6], Tag7,...])";
        echo "<br /> addChild(Tag1, Tag2, Tag3,...)";
        echo "<br />";
        echo "=============================================== <br />";
        echo "= Yukanoe Trace : START <br />";
        echo "===============================================<br />";
        echo 'Error: ', $e->getMessage(), "<br />";
        echo "<br />";
        foreach ($e->getTrace() as $value) {
            echo "=== Trace ================================== <br />";
            echo "=<br />";
            echo "= . . . File: <b>$value[file]</b> - line: <b>$value[line]</b><br />";
            echo "= . . . Function: $value[class]$value[type]$value[function](<b>Args</b>)<br />";
            echo "= . . . <b>var_dump(Args)</b> Detail:<br />";
            echo "= . . . ";
            var_dump($value['args']);
            echo "<br />";
            echo "=<br />";
            echo "============================================<br />";
        }
    }

    public function insertAfter(Tag $tag): Tag
    {
        if ($tag->parent instanceof Tag) {
            $offset = array_search($tag, $tag->parent->child) + 1;
            if ($offset >= 0) {
                array_splice($tag->parent->child, $offset, 0, [$this]);
            }
        }
        return $this;
    }

    public function insertBefore(Tag $tag): Tag
    {
        if ($tag->parent instanceof Tag) {
            $offset = array_search($tag, $tag->parent->child);
            if ($offset >= 0) {
                array_splice($tag->parent->child, $offset, 0, [$this]);
            }
        }
        return $this;
    }

    public function removeLastChild(): Tag
    {
        array_pop($this->child);
        return $this;
    }

    public function removeFirstChild(): Tag
    {
        array_shift($this->child);
        return $this;
    }

    public function removeChild(Tag $tag): Tag
    {
        if (($offset = array_search($tag, $this->child)) !== false) {
            array_splice($this->child, $offset, 1);
        }
        return $this;
    }

    public function removeChildIndex(int $index): Tag
    {
        if (isset($this->child[$index])) {
            $this->removeChild($this->child[$index]);
        }
        return $this;
    }

    // Visibility methods

    public function hide(): Tag
    {
        $this->attribute['data-yukanoe-hidden'] = 'hidden';
        return $this;
    }

    public function show(): Tag
    {
        unset($this->attribute['data-yukanoe-hidden']);
        return $this;
    }

    public function restrict(): Tag
    {
        $this->attribute['data-yukanoe-restricted'] = 'restricted';
        return $this;
    }

    public function unrestrict(): Tag
    {
        unset($this->attribute['data-yukanoe-restricted']);
        return $this;
    }

    public function empty(): Tag
    {
        $this->name = self::$emptyTag;
        $this->attribute = [];
        $this->child = [];
        $this->text = '';
        return $this;
    }

    public function destroy(): Tag
    {
        $this->empty();
        $this->parent->removeChild($this);
        return $this;
    }

    // Output methods

    public function get(): string
    {
        $buffer = '';
        $this->flushBuffer($buffer);
        return $buffer;
    }

    public function exportYD(array &$tagName = []): array
    {
        if (isset($this->attribute['data-yukanoe-id'])) {
            $tagName[$this->attribute['data-yukanoe-id']] = $this;
        }
        foreach ($this->child as &$value) {
            $value->exportYD($tagName);
        }
        return $tagName;
    }

    public static function restrictText(string $str): string
    {
        return htmlspecialchars($str);
    }

    public function flushBuffer(string &$buffer): void
    {
        $buffer ??= "";

        if (isset($this->attribute['data-yukanoe-hidden'])) {
            return;
        }

        $restricted = isset($this->attribute['data-yukanoe-restricted']);

        if ($this->name == 'html') {
            $buffer .= self::$documentType;
        }

        $buffer .= '<' . $this->name;

        foreach ($this->attribute as $key => $value) {
            if ($restricted) {
                $value = self::restrictText($value);
            }
            $buffer .= " $key=\"$value\"";
        }

        if (in_array($this->name, self::$singletonTags)) {
            $buffer .= ' />';
        } else {
            $buffer .= '>';
            foreach ($this->child as $value) {
                $value->flushBuffer($buffer);
            }
            if ($restricted) {
                $this->text = self::restrictText($this->text);
            }
            $buffer .= $this->text . '</' . $this->name . '>';
        }
    }

    public function flush(): void
    {
        if (isset($this->attribute['data-yukanoe-hidden'])) {
            return;
        }

        $restricted = isset($this->attribute['data-yukanoe-restricted']);

        if ($this->name == 'html') {
            echo self::$documentType;
        }

        echo '<' . $this->name;

        foreach ($this->attribute as $key => $value) {
            if ($restricted) {
                $value = self::restrictText($value);
            }
            echo " $key=\"$value\" ";
        }

        if (in_array($this->name, self::$singletonTags)) {
            echo ' />';
        } else {
            echo '>';
            foreach ($this->child as $value) {
                $value->flush();
            }
            if ($restricted) {
                $this->text = self::restrictText($this->text);
            }
            echo $this->text . '</' . $this->name . '>';
        }
    }

    public function flushByResponse(mixed &$response): void
    {
        if (isset($this->attribute['data-yukanoe-hidden'])) {
            return;
        }

        if ($this->name == 'html') {
            $response->write(self::$documentType);
        }

        $response->write('<' . $this->name);

        foreach ($this->attribute as $key => $value) {
            $response->write(" $key=\"$value\" ");
        }

        if (in_array($this->name, self::$singletonTags)) {
            $response->write(' />');
        } else {
            $response->write('>');
            foreach ($this->child as $value) {
                $value->flushByResponse($response);
            }
            $response->write($this->text . '</' . $this->name . '>');
        }
    }

    public function newTrace(): void
    {
        self::$traceCounter = 0;
        $this->trace();
    }

    public function trace(): void
    {
        self::$traceCounter++;
        $globalVarName = 'notRootGlobals#' . self::$traceCounter;
        foreach ($GLOBALS as $var_name => $value) {
            if ($value === $this) {
                $globalVarName = '$' . $var_name;
            }
        }

        if ($this->name == 'html') {
            echo '[!DOCTYPE html]';
        }

        echo "<b> $globalVarName </b> = " . '[' . $this->name;
        foreach ($this->attribute as $key => $value) {
            if (in_array($key, ['name', 'id', 'data-yukanoe-hidden'])) {
                echo " <b style=\"color:red;\">$key=\"" . $value . "\"</b>";
            } else {
                echo " $key=\"$value\" ";
            }
        }

        if (in_array($this->name, self::$singletonTags)) {
            echo ' /] <br />';
        } else {
            echo '] <br />';
            foreach ($this->child as $key => $value) {
                echo "<b>{$globalVarName}-></b>child[$key] = ";
                $value->trace();
            }
            echo $this->text . '[/' . $this->name . ']';
            echo "<br />";
        }
    }
}