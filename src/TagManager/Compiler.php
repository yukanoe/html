<?php
namespace Yukanoe\HTML\TagManager;

use Yukanoe\HTML\Tag;
/**
 *   plan:
 *   $this->tagRoot    : ROOT Tag[x]
 *   $this->tagName[?] : Alias data-yukanoe-id=? [x]
 *   $this->tagId[?]   : Alias id=? []
 */
class Compiler
{
    private $listAlias      = [];
    private $avStatements   = [];
    private $avCounter      = 0;

    public $tag;
    public $tagRoot;
    public $tagName = [];

    public static $regVarName = 'av';
    public static $aliVarName = 'avn';

    function __construct()
    {
        return $this;
    }

    public function getTagRoot()
    {
        return $this->tagRoot;
    }

    public function getTagName()
    {
        return $this->tagName;
    }

    public function compileRealTime($domDocument)
    {
        $this->runBuildTool($domDocument);
        $this->getTagAlias();
        $this->tagRoot = $this->tag[1] ?? NULL;
        return $this->getTagRoot();
    }

    public function free()
    {
        $this->avStatements = [];
        $this->avCounter    = 0;
    }

    public function fixSingleQuote($v_innerHTML)
    {
        // Apostrophe
        return str_replace('\'', '\\\'', $v_innerHTML);
    }

    public function checkTagAlias($attribute, $yukanoeid, $id)
    {
        //is data-yukanoe-id
        if($attribute != 'data-yukanoe-id')
            return;
        //checking yukanoe-id
        if(!$yukanoeid || strlen($yukanoeid) > 255 ) 
            return;
        $this->listAlias[$yukanoeid] = $id;
    }

    public function getTagAlias()
    {
        $Result = [];
        $AV     = self::$regVarName;
        $AVN    = self::$aliVarName;
        foreach ($this->listAlias as $key => $id) {
            array_push($Result, "\${$AVN}['{$key}'] = \${$AV}[{$id}];");
            $this->tagName[$key] = $this->tag[$id];
        }
        return $Result;
    }

    public function runBuildTool($Root, $Leaf = 0) 
    {    
        if ( !is_object($Root) )
            return [];

        $Logger = new Logger;
        $YUT_AV = "\$".self::$regVarName;

        //handle classic node
        if($Root->nodeType == XML_ELEMENT_NODE) {
            $this->avCounter++;

            //START Attribute v2 - won
            $string_att = "[]";
            if($Root->hasAttributes()) {
                $string_att = "[";
                foreach($Root->attributes as $attribute) {
                    $attribute_name  = $this->fixSingleQuote($attribute->name);
                    $attribute_value = $this->fixSingleQuote($attribute->value);
                    $string_att.= "'{$attribute_name}'=>'{$attribute_value}',";
                    $this->checkTagAlias($attribute->name, $attribute->value, $this->avCounter);
                }
                $string_att .= "]";
                $string_att = preg_replace('/\,\]$/si', ']', $string_att);
            }
            

            
            $Logger->debug("START Render of {$this->avCounter} ");

            // gen statement
            $Statement = "{$YUT_AV}[{$this->avCounter}] = new Tag('{$Root->nodeName}', $string_att, '');";
            // exec statement
            $arrAttribute = [];
            if($Root->hasAttributes()) {
                foreach($Root->attributes as $attribute) {
                    $arrAttribute[$attribute->name] = $attribute->value;
                }
            }
            $this->tag[$this->avCounter] = new Tag(
                $Root->nodeName,
                $arrAttribute,
                ''
            );

            $Logger->info("$Statement");

            array_push($this->avStatements, $Statement);

            if($Leaf){
                $Logger->debug("AddLink");
                $Statement =  "{$YUT_AV}[{$Leaf}]->addChild({$YUT_AV}[{$this->avCounter}]);";
                $this->tag[$Leaf]->addChild($this->tag[$this->avCounter]);
                $Logger->info("$Statement");
                array_push($this->avStatements, $Statement);
            }

            if($Root->hasChildNodes()) {
                $rparent  = $this->avCounter;  //temp
                $children = $Root->childNodes;
                $numchild = $children->length;

                // child = 1 && isTextNode => inner = child->inner
                if($numchild == 1  && 
                    ($children->item(0)->nodeType == XML_TEXT_NODE ||
                     $children->item(0)->nodeType == XML_CDATA_SECTION_NODE) ){ 

                        $Text = $children->item(0)->nodeValue;
                        $Text = $this->fixSingleQuote($Text);
                        
                        //echo "<br />IGOTIT@1";
                        // gen statemtnt
                        $Statement = "{$YUT_AV}[{$this->avCounter}]->text = '$Text';";
                        // exec statement
                        $this->tag[$this->avCounter]->text = $Text;

                        $Logger->info("$Statement");
                        array_push($this->avStatements, $Statement);
                    
                } else{
                    $i = 0;
                    while( $i < $children->length) {
                        $Logger->debug("BEGIN reading Child $i of {$this->avCounter}");
                        $child = $this->runBuildTool( $children->item($i) , $rparent);
                        $Logger->debug("END   reading Child $i of {$this->avCounter}");
                        $i++;
                    }
                }
            }

            

        //handle text node
        } elseif( $Root->nodeType == XML_TEXT_NODE 
               || $Root->nodeType == XML_CDATA_SECTION_NODE ) {
            
            
            $value   = trim($Root->nodeValue);
            $PValue  = $Root->nodeValue;
            $tagName = $Root->nodeName;
            $tagName = 'yukanoe-text';   // span

            if($value){

                $this->avCounter++;

                $Statement = "{$YUT_AV}[{$this->avCounter}] = new Tag('{$tagName}', [], '');";
                $this->tag[$this->avCounter] = new Tag($tagName, [], '');

                $Logger->info("$Statement");
                array_push($this->avStatements, $Statement);

                $Logger->debug("SET XML_TEXT_NODE = ".$PValue);

                $PValue    = $this->fixSingleQuote($PValue);
                $Statement = "{$YUT_AV}[{$this->avCounter}]->text = '$PValue';";
                $this->tag[$this->avCounter]->text = $Root->nodeValue;

                $Logger->info("$Statement");
                array_push($this->avStatements, $Statement);

                if($Leaf){
                    $Logger->debug("AddLink");
                    $Statement =  "{$YUT_AV}[{$Leaf}]->addChild({$YUT_AV}[{$this->avCounter}]);";
                    $this->tag[$Leaf]->addChild($this->tag[$this->avCounter]);

                    $Logger->info("$Statement");
                    array_push($this->avStatements, $Statement);
                }

            } else {
                $Logger->debug("XML_TEXT_NODE == NULL : ByPass");
            }

        }


        return $this->avStatements;

    }






}
