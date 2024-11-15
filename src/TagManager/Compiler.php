<?php

/**
 * Yukanoe HTML Compiler
 * Convert HTML DOMDocument to Yukanoe Tag
 * Details:
 *   Plan:
 *   - $this->tagRoot    : ROOT Tag[x]
 *   - $this->tagName[?] : Alias data-yukanoe-id=? [x]
 *   - $this->tagId[?]   : Alias id=? []
 */

namespace Yukanoe\HTML\TagManager;

use DOMElement;
use DOMNameSpaceNode;
use DOMNode;
use Yukanoe\HTML\Tag;

class Compiler
{
    public array $listAlias = [];
    public array $avStatements = [];
    public int $avCounter = 0;

    public array $tag;
    public ?Tag $tagRoot = null;
    public array $tagName = [];

    public static string $regVarName = 'av';
    public static string $aliVarName = 'avn';

    public static string $defaultTagName = 'yukanoe-text';

    public function getTagRoot(): ?Tag
    {
        return $this->tagRoot;
    }

    public function getTagName(): array
    {
        return $this->tagName;
    }

    public function compileRealTime(DOMElement|DOMNameSpaceNode|DOMNode|null $domDocument): ?Tag
    {
        $this->runBuildTool($domDocument);
        $this->getTagAlias();
        $this->tagRoot = $this->tag[1] ?? NULL;
        return $this->getTagRoot();
    }

    public function free(): void
    {
        $this->avStatements = [];
        $this->avCounter = 0;
    }

    public function fixSingleQuote(string $innerHTML): string
    {
        // Escape single quotes
        return str_replace('\'', '\\\'', $innerHTML);
    }

    public function checkTagAlias(string $attribute, string $yukanoeId, int $id): void
    {
        // Check if attribute is data-yukanoe-id
        if ($attribute !== 'data-yukanoe-id') {
            return;
        }
        // Validate yukanoe-id
        if (!$yukanoeId || strlen($yukanoeId) > 255) {
            return;
        }
        $this->listAlias[$yukanoeId] = $id;
    }

    public function getTagAlias(): array
    {
        $result = [];
        $regVarName = self::$regVarName;
        $aliVarName = self::$aliVarName;
        foreach ($this->listAlias as $key => $id) {
            $result[] = "\${$aliVarName}['{$key}'] = \${$regVarName}[{$id}];";
            $this->tagName[$key] = $this->tag[$id];
        }
        return $result;
    }

    public function runBuildTool(DOMElement|DOMNameSpaceNode|DOMNode|null $rootNode, int $parentNodeId = 0): array
    {
        if (!is_object($rootNode)) {
            return [];
        }

        $logger = new Logger;
        $regVarName = "\$" . self::$regVarName;

        // Handle element nodes
        if ($rootNode->nodeType === XML_ELEMENT_NODE) {
            $this->avCounter++;

            // Process attributes
            $attributesString = "[]";
            if ($rootNode->hasAttributes()) {
                $attributesString = "[";
                foreach ($rootNode->attributes as $attribute) {
                    $attributeName = $this->fixSingleQuote($attribute->name);
                    $attributeValue = $this->fixSingleQuote($attribute->value);
                    $attributesString .= "'{$attributeName}'=>'{$attributeValue}',";
                    $this->checkTagAlias($attribute->name, $attribute->value, $this->avCounter);
                }
                $attributesString = rtrim($attributesString, ',') . "]";
            }

            $logger->debug("START Render of {$this->avCounter}");

            // Generate and execute statement
            $statement = "{$regVarName}[{$this->avCounter}] = new Tag('{$rootNode->nodeName}', $attributesString, '');";
            $attributesArray = [];
            if ($rootNode->hasAttributes()) {
                foreach ($rootNode->attributes as $attribute) {
                    $attributesArray[$attribute->name] = $attribute->value;
                }
            }
            $this->tag[$this->avCounter] = new Tag($rootNode->nodeName, $attributesArray, '');

            $logger->info($statement);
            $this->avStatements[] = $statement;

            if ($parentNodeId) {
                $logger->debug("AddLink");
                $statement = "{$regVarName}[{$parentNodeId}]->addChild({$regVarName}[{$this->avCounter}]);";
                $this->tag[$parentNodeId]->addChild($this->tag[$this->avCounter]);
                $logger->info($statement);
                $this->avStatements[] = $statement;
            }

            if ($rootNode->hasChildNodes()) {
                $currentParentId = $this->avCounter;
                $childNodes = $rootNode->childNodes;
                $numChildNodes = $childNodes->length;

                // Handle single text node child
                if ($numChildNodes == 1 && ($childNodes->item(0)->nodeType == XML_TEXT_NODE || $childNodes->item(0)->nodeType == XML_CDATA_SECTION_NODE)) {
                    $textContent = $childNodes->item(0)->nodeValue;
                    $textContent = $this->fixSingleQuote($textContent);

                    $statement = "{$regVarName}[{$this->avCounter}]->text = '$textContent';";
                    $this->tag[$this->avCounter]->text = $textContent;

                    $logger->info($statement);
                    $this->avStatements[] = $statement;
                } else {
                    for ($i = 0; $i < $childNodes->length; $i++) {
                        $logger->debug("BEGIN reading Child $i of {$this->avCounter}");
                        $this->runBuildTool($childNodes->item($i), $currentParentId);
                        $logger->debug("END reading Child $i of {$this->avCounter}");
                    }
                }
            }

        // Handle text and CDATA nodes
        } elseif ($rootNode->nodeType == XML_TEXT_NODE || $rootNode->nodeType == XML_CDATA_SECTION_NODE) {
            $nodeValue = trim($rootNode->nodeValue);
            $rawNodeValue = $rootNode->nodeValue;
            $tagName = self::$defaultTagName;

            if ($nodeValue) {
                $this->avCounter++;

                $statement = "{$regVarName}[{$this->avCounter}] = new Tag('{$tagName}', [], '');";
                $this->tag[$this->avCounter] = new Tag($tagName, [], '');

                $logger->info($statement);
                $this->avStatements[] = $statement;

                $logger->debug("SET XML_TEXT_NODE = " . $rawNodeValue);

                $escapedNodeValue = $this->fixSingleQuote($rawNodeValue);
                $statement = "{$regVarName}[{$this->avCounter}]->text = '$escapedNodeValue';";
                $this->tag[$this->avCounter]->text = $rawNodeValue;

                $logger->info($statement);
                $this->avStatements[] = $statement;

                if ($parentNodeId) {
                    $logger->debug("AddLink");
                    $statement = "{$regVarName}[{$parentNodeId}]->addChild({$regVarName}[{$this->avCounter}]);";
                    $this->tag[$parentNodeId]->addChild($this->tag[$this->avCounter]);

                    $logger->info($statement);
                    $this->avStatements[] = $statement;
                }
            } else {
                $logger->debug("XML_TEXT_NODE == NULL : ByPass");
            }
        }

        return $this->avStatements;
    }
}