<?php

/**
 * Minimal logger for debugging purposes.
 */

namespace Yukanoe\HTML\TagManager;

class Logger
{
    public static string $mode = 'none'; // "none",   "debug",   "info"
    public static string $newline = "\n";   // "\n",     "<br />"

    public function info($text): void
    {
        if (self::$mode != 'none')
            echo "\n[INFO] $text" . self::$newline;
    }

    public function debug($text): void
    {
        if (self::$mode == 'debug')
            echo "\n[DEBUG] $text" . self::$newline;
    }

}
