<?php

namespace Yukanoe\HTML;

use DOMElement;
use DOMNameSpaceNode;
use DOMNode;
use Yukanoe\HTML\TagManager\Compiler;
use Yukanoe\HTML\TagManager\Reducer;
use Yukanoe\HTML\TagManager\IO;

class TagManager
{
    private  DOMElement|DOMNameSpaceNode|DOMNode|null $domDocument;
    private array $mainArr;
    private array $aliasArr;
    private IO $IO;

    public function __construct()
    {
        $this->IO = new IO();
    }

    public function read(string $v_auto): static
    {
        $this->domDocument = $this->IO->readHTMLFile($v_auto);
        return $this;
    }

    public function readRaw(string $v_auto): static
    {
        $this->domDocument = $this->IO->readHTMLRaw($v_auto);
        return $this;
    }

    public function readRealTimeRaw(string $html): ?Tag
    {
        $this->domDocument = $this->IO->readHTMLRaw($html);
        return (new Compiler())->compileRealTime($this->domDocument);
    }

    public function readRealTime(string $file): ?Tag
    {
        $domDocument = $this->IO->readHTMLFile($file);
        return (new Compiler())->compileRealTime($domDocument);
    }

    public function build(bool $Reducing = true): static
    {
        $Compiler = new Compiler();
        $this->mainArr = $Compiler->runBuildTool($this->domDocument);
        $this->aliasArr = $Compiler->getTagAlias();

        if ($Reducing) {
            $Reducer = new Reducer();
            $this->mainArr = $Reducer->reduce($this->mainArr);
        }

        return $this;
    }

    public function save(string $phpfile = 'render.php'): static
    {
        $this->IO->save($this->mainArr, $this->aliasArr, $phpfile);
        return $this;
    }

    public function get(string $phpfile = 'render.php'): string
    {
        return $this->IO->toString($this->mainArr, $this->aliasArr, $phpfile);
    }

    public function view(): static
    {
        $this->IO->view($this->mainArr);
        return $this;
    }

    public function configureVarName(string $index = 'av', string $alias = 'avn'): static
    {
        if ($index) {
            Compiler::$regVarName = $index;
        }
        if ($alias) {
            Compiler::$aliVarName = $alias;
        }
        return $this;
    }

    public function autoBuild(string $indir = '.', string $outdir = '.', string $namespace = ''): void
    {
        if (!mkdir($outdir, 0777, true) && !is_dir($outdir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $outdir));
        }

        $option = \RecursiveDirectoryIterator::SKIP_DOTS;
        $directory = new \RecursiveDirectoryIterator($indir, $option);
        $iterator = new \RecursiveIteratorIterator($directory);
        $regex = new \RegexIterator($iterator, '/^.+\.html$/i');

        foreach ($regex as $filename) {
            $fileread = $filename;
            $filesave = $outdir . substr($fileread, strlen($indir));
            $filesave = substr($filesave, 0, -strlen('html')) . 'php';

            if (!mkdir(dirname($filesave), 0777, true) && !is_dir(dirname($filesave))) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', dirname($filesave)));
            }

            if (filesize($fileread) > 0) {
                echo "\n[INFO] {$fileread} => {$filesave}";
                $this->read($fileread)->build()->save($filesave);
            }
        }
    }
}