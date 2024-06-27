<?php

namespace Yukanoe\HTML;

class BootStrap
{
    function __construct()
    {
        foreach ($this->listFile(__DIR__."/../src") as $file) {
            require_once $file;
        }
    }

    function listFile(string $parentDir): \RegexIterator
    {
        $dir      = $parentDir;
        $filters  = "/\.php$/i";
        $dirIter  = new \RecursiveDirectoryIterator(
            $dir,
            \RecursiveDirectoryIterator::UNIX_PATHS
        );
        $iter  = new \RecursiveIteratorIterator(
            $dirIter,
            \RecursiveIteratorIterator::SELF_FIRST
        );
        $regexIter = new \RegexIterator($iter, $filters);
        return $regexIter;
    }
}

$bootstrap = new BootStrap;
