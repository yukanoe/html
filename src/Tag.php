<?php

namespace Yukanoe\HTML;
/**
 *
 *   Tag::$singletonTags = Tag::$singletonAllTags;
 *   Tag::$autoFlush     = false;
 *   data-yukanoe-hidden = "hiden"
 *
 */
class Tag
{
    public string $name = '';
    public string $text = '';
    public array $attribute = [];
    public ?Tag $parent = NULL;
    public array $child = [];

    // Configurable
    public static bool  $autoFlush = true;
    public static string $documentType = '<!DOCTYPE html>';
    public static array  $singletonTags = ['img', 'meta', 'input', 'link', 'br', 'source', 'hr', 'area', 'source', 'track'];

    // Non-configurable
    public static array $singletonAllTags = ['area', 'base', 'basefont', 'bgsound', 'br', 'col', 'command', 'embed', 'frame', 'hr', 'image', 'img', 'input', 'isindex', 'keygen', 'link', 'menuitem', 'meta', 'nextid', 'param', 'source', 'track', 'wbr'];
    public static int $traceCounter = 0;

    public function __construct(string $name = '', array $attribute = [], string $text = '')
    {
        // init
        $this->name = $name;
        $this->attribute = $attribute;
        $this->text = $text;
    }

    public function __destruct()
    {
        if (self::$autoFlush)
            if ($this->name == 'html')
                $this->flush();
    }

    public function __clone()
    {
        foreach ($this->child as &$value)
            $value = clone $value;
    }

    public function __set($key, $value)
    {
        if ($key = 'addChild')
            $this->addChild($value);
    }

    //TRUE-SET-MODE:START
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
        if (!isset($vAtt[1]))
            return $this;
        if (is_string($vAtt[0]) && is_string($vAtt[1]))
            $this->attribute[$vAtt[0]] = $vAtt[1];
        return $this;
    }

    public function getAttribute(): array
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

    public function setParent($v): Tag
    {
        $this->parent = $v;
        return $this;
    }

    public function getParent(): ?Tag
    {
        return $this->parent;
    }

    //TRUE-SET-MODE:END

    public function appendChild($tag): Tag
    {
        $tag->setParent($this);
        $this->child[] = $tag;
        return $this;
    }

    public function prependChild($tag): Tag
    {
        $tag->setParent($this);
        array_unshift($this->child, $tag);
        return $this;
    }


    public function getRoot()
    {
        $Find = $this;
        while ($Find->parent)
            $Find = $Find->parent;
        if ($Find instanceof Tag)
            return $Find;
        return $this;
    }

    public function getAncestorByName($search): Tag
    {
        $Find = $this;
        while ($Find->parent) {
            $Find = $Find->parent;
            if ($Find->name == $search)
                return $Find;
        }
        return $this;
    }

    public function getChildsByTagName(string $name = '', array &$tags = []): Tag
    {
        if (!isset($tags))
            $tags = [];
        if ($this->name == $name) {
            $tags[] = $this;
        }
        foreach ($this->child as &$value) {
            $value->getChildsByTagName($name, $tags);
        }
        return $tags;
    }


    // Add CHILD FUNCTION LIST
    public function addChild(...$v): Tag
    {
        //Variable-length argument lists
        foreach ($v as $Element) {
            //Array
            if (is_array($Element))
                foreach ($Element as $value)
                    $this->addChild($value);
            else {
                //checking safe tag
                try {
                    if ($Element instanceof Tag)
                        $this->appendChild($Element);
                    else
                        throw new \Exception('addChild(non-\Yukanoe\HTML\Tag)');
                } catch (\Exception $e) {
                    $this->addChildException($e);
                }
            }

        }
        return $this;
    }

    //*Specail
    public function addChildException($e): void
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

    // Injection
    public function insertAfter($tag): Tag
    {
        if (!($tag->parent instanceof Tag))
            return $this;
        $offset = array_search($tag, $tag->parent->child) + 1;
        if ($offset >= 0)
            array_splice($tag->parent->child, $offset, 0, [$this]);
        return $this;
    }

    public function insertBefore($tag): Tag
    {
        if (!($tag->parent instanceof Tag))
            return $this;
        $offset = array_search($tag, $tag->parent->child);
        if ($offset >= 0)
            array_splice($tag->parent->child, $offset, 0, [$this]);
        return $this;
    }

    // Remove child
    public function removeLastChild(): Tag
    {
        $LastChild = array_pop($this->child);
        return $this;
    }

    public function removeFirstChild(): Tag
    {
        $FirstChild = array_shift($this->child);
        return $this;
    }

    public function removeChild($tag): Tag
    {
        if (($offset = array_search($tag, $this->child)) !== false)
            array_splice($this->child, $offset, 1);
        return $this;
    }

    public function removeChildIndex($Index): Tag
    {
        if (isset($this->child[$Index]))
            $this->removeChild($this->child[$Index]);
        return $this;
    }

    //hidden / empty
    public function hide(): Tag
    {
        $this->attribute['data-yukanoe-hidden'] = 'hidden';
        return $this;
    }

    public function show(): Tag
    {
        if (isset($this->attribute['data-yukanoe-hidden']))
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
        if (isset($this->attribute['data-yukanoe-restricted']))
            unset($this->attribute['data-yukanoe-restricted']);
        return $this;
    }

    public function empty(): Tag
    {
        $this->name = 'yukanoe-empty';
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

    public function get(): string
    {
        $code_html = '<' . $this->name;
        foreach ($this->attribute as $key => $value) {
            $code_html .= ' ' . $key . '="' . $value . '"';
        }
        $code_html .= '> ' . $this->text . '</' . $this->name . '>';
        return $code_html;
    }

    public function exportYD(array &$tagName = []): array
    {
        if (!isset($tagName))
            $tagName = [];
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

        //attribute[data-yukanoe-hidden] == "true/hidden/.." => skip;
        if (isset($this->attribute['data-yukanoe-hidden']))
            return;

        $restricted = isset($this->attribute['data-yukanoe-restricted']);

        //prepend doctype html
        if ($this->name == 'html') {
            //echo self::$documentType;
            $buffer .= self::$documentType;
        }

        //tag name + att
        //echo '<'.$this->name;
        $buffer .= '<' . $this->name;

        if (is_array($this->attribute) || is_object($this->attribute))
            foreach ($this->attribute as $key => $value) {
                if ($restricted) {
                    $value = self::restrictText($value);
                }
                $buffer .= " $key=\"$value\" ";
            }

        if (in_array($this->name, self::$singletonTags)) {
            //Singleton tag -> self closing
            $buffer .= ' />';
        } else {
            //Multipart Tag
            $buffer .= '>';
            if (is_array($this->child) || is_object($this->child))
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
        //attribute[data-yukanoe-hidden] == "true/hidden/.." => skip;
        if (isset($this->attribute['data-yukanoe-hidden']))
            return;

        $restricted = isset($this->attribute['data-yukanoe-restricted']);

        //prepend doctype html
        if ($this->name == 'html')
            echo self::$documentType;

        //tag name + att
        echo '<' . $this->name;

        if (is_array($this->attribute) || is_object($this->attribute))
            foreach ($this->attribute as $key => $value) {
                if ($restricted) {
                    $value = self::restrictText($value);
                }
                echo " $key=\"$value\" ";
            }

        if (in_array($this->name, self::$singletonTags)) {
            //Singleton tag -> self closing 
            echo ' />';
        } else {
            //Multipart Tag
            echo '>';
            if (is_array($this->child) || is_object($this->child))
                foreach ($this->child as $value) {
                    $value->flush();
                }
            if ($restricted) {
                $this->text = self::restrictText($this->text);
            }
            echo $this->text . '</' . $this->name . '>';
        }

    }

    // DEVEL
    public function flushByResponse(mixed &$response): void
    {
        //attribute[data-yukanoe-hidden] == "true" => skip;
        if (isset($this->attribute['data-yukanoe-hidden']))
            return;

        //prepend doctype html
        if ($this->name == 'html')
            $response->write(self::$documentType);
        //echo self::$documentType;

        //tag name + att
        //echo '<'.$this->name;
        $response->write('<' . $this->name);

        if (is_array($this->attribute) || is_object($this->attribute))
            foreach ($this->attribute as $key => $value) {
                //echo " $key=\"$value\" ";
                $response->write(" $key=\"$value\" ");
            }

        if (in_array($this->name, self::$singletonTags)) {
            //Singleton tag -> self closing
            //echo ' />';
            $response->write(' />');
        } else {
            //Multipart Tag
            //echo '>';
            $response->write('>');
            if (is_array($this->child) || is_object($this->child))
                foreach ($this->child as $value) {
                    $value->flushByResponse($response);
                }
            $response->write($this->text . '</' . $this->name . '>');
            //echo $this->text.'</'.$this->name.'>';
        }

    }

    public function newTrace(): void
    {
        self::$traceCounter = 0;
        $this->trace();
    }

    public function trace(): void
    {
        self::$traceCounter = self::$traceCounter + 1 ?? 0;
        $GlobalVarName = 'notRootGlobals#' . self::$traceCounter;
        foreach ($GLOBALS as $var_name => $value) {
            if ($value === $this) {
                $GlobalVarName = '$' . $var_name;
            }
        }

        //doctype html
        if ($this->name == 'html')
            echo '[!DOCTYPE html]';
        //tag name + att
        echo "<b> $GlobalVarName </b> = " . '[' . $this->name;
        if (is_array($this->attribute) || is_object($this->attribute))
            foreach ($this->attribute as $key => $value) {
                if (in_array($key, ['name', 'id', 'data-yukanoe-hidden']))
                    echo " <b style=\"color:red;\">$key=\"" . $value . "\"</b>"; //DEV VIEW
                else
                    echo " $key=\"$value\" ";
            }

        if (in_array($this->name, self::$singletonTags)) {
            //single 
            echo ' /] <br />';
        } else {
            //double default
            echo '] <br />';
            if (is_array($this->child) || is_object($this->child))
                foreach ($this->child as $key => $value) {
                    echo "<b>{$GlobalVarName}-></b>child[$key] = ";
                    $value->trace();
                }
            echo $this->text . '[/' . $this->name . ']';
            echo "<br />";
        }

    }

}
