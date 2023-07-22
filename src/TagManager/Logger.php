<?php
namespace Yukanoe\HTML\TagManager;
/**
 * 
 * DEBUG-ONLY
 * 
 */
class Logger
{
    
    public static $mode    = 'none'; // "none",   "debug",   "info"
    public static $newline = "\n";   // "\n",     "<br />"

    function __construct()
    {
        return $this;
    }

    public function info($text)
    {
        if(self::$mode != 'none')
            echo "\n[INFO] $text".self::$newline;
    }

    public function debug($text)
    {
        if(self::$mode == 'debug')
            echo "\n[DEBUG] $text".self::$newline;
    }
    

}