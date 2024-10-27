<?php

namespace Yukanoe\HTML;

use \Yukanoe\HTML\TagManager\Compiler;
use \Yukanoe\HTML\TagManager\Reducer;
use \Yukanoe\HTML\TagManager\IO;


class TagManager
{
    private $domDocument;
    private $mainArr;
    private $aliasArr;
    private $IO;


    public function __construct()
    {
        $this->IO = new IO;
    }

    public function read($v_auto): static
    {
        $this->domDocument = $this->IO->readHTMLFile($v_auto);
        return $this;
    }

    public function readraw($v_auto): static
    {
        $this->domDocument = $this->IO->readHTMLRaw($v_auto);
        return $this;
    }

    public function readRealTimeRaw($html): ?Tag
    {
        $this->domDocument = $this->IO->readHTMLRaw($html);
        return (new Compiler)->compileRealTime($this->domDocument);
    }

    public function readRealTime($file): ?Tag
    {
        $domDocument = $this->IO->readHTMLFile($file);
        return (new Compiler)->compileRealTime($domDocument);
    }

    public function build($Reducing = true): static
    {
        //compiling
        $Compiler = new Compiler;
        $this->mainArr = $Compiler->runBuildTool($this->domDocument);
        $this->aliasArr = $Compiler->getTagAlias();
        //checking
        if (!$Reducing)
            return $this;
        //reducing
        $Reducer = new Reducer;
        $this->mainArr = $Reducer->reduce($this->mainArr);
        return $this;
    }

    public function save($phpfile = 'render.php'): static
    {
        $this->IO->save($this->mainArr, $this->aliasArr, $phpfile);
        return $this;
    }

    public function get($phpfile = 'render.php'): string
    {
        return $this->IO->toString($this->mainArr, $this->aliasArr, $phpfile);
    }

    public function view(): static
    {
        $this->IO->view($this->mainArr);
        return $this;
    }

    public function configureVarName($index = 'av', $alias = 'avn'): static
    {
        if ($index)
            Compiler::$regVarName = $index;
        if ($alias)
            Compiler::$aliVarName = $alias;
        return $this;
    }

    public function autoBuild($indir = '.', $outdir = '.', $namespace = ''): void
    {
        if (!is_dir($outdir)) {
            mkdir($outdir, 0777, true);
        }
        $option = \RecursiveDirectoryIterator::SKIP_DOTS;
        $directory = new \RecursiveDirectoryIterator($indir, $option);
        $iterator = new \RecursiveIteratorIterator($directory);
        $regex = new \RegexIterator($iterator, '/^.+\.html$/i');
        foreach ($regex as $filename) {
            $fileread = $filename;
            $filesave = $outdir . substr($fileread, strlen($indir));
            $filesave = substr($filesave, 0, (-1) * strlen('html')) . 'php';
            if (!is_dir(dirname($filesave))) {
                mkdir(dirname($filesave), 0777, true);
            }
            if (filesize($fileread) > 0) {
                echo "\n[INFO] {$fileread} => {$filesave}";
                $this->read($fileread)->build()->save($filesave);
            }
        }

    }


}

