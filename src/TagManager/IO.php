<?php

namespace Yukanoe\HTML\TagManager;
/**
 *
 */
class IO
{
    private int $currentIncludeFile = 0;
    private int $maxIncludeFile = 100;

    public function readHTMLStringFromFile(string $filepath = 'index.html'): string|null
    {
        if (!is_file($filepath))
            return null;

        if ($this->currentIncludeFile++ > $this->maxIncludeFile)
            throw new \Exception('Max include files.');

        $raw = file_get_contents($filepath);

        $pattern = '/\<\!\-\- *data-yukanoe-include\=\"(.+?\.html)\" *\-\-\>/';
        $subject = $raw;
        $matches = null;

        preg_match_all($pattern, $subject, $matches);

        $i = 0;
        $max = count($matches[0]);
        while ($i < $max) {
            $include_html = $matches[0][$i] ?? '';
            $include_file = $matches[1][$i] ?? '';
            //__dir__ => currentDir
            if (strtolower(substr($include_file, 0, 7)) == "__dir__") {
                $currentdir = pathinfo($filepath)['dirname'] ?? '';
                $include_file = $currentdir . substr($include_file, 7);
            }
            if (!is_file($include_file)) {
                //try with samefolder
                $currentdir = pathinfo($filepath)['dirname'] ?? '';
                $include_file = $currentdir . '/' . $include_file;
                if (!is_file($include_file)) {
                    //throw new Exception("Error Processing Request", 1);
                    break;
                }
            }

            //$include_contents = file_get_contents($include_file);
            $include_contents = $this->readHTMLStringFromFile($include_file);
            $raw = str_replace($include_html, $include_html . "\n" . $include_contents, $raw);
            $i++;
        }
        return $raw;

    }

    public function readHTMLFile(string $filepath = 'index.html'): \DOMNode|\DOMElement|\DOMNameSpaceNode|null
    {
        $this->currentIncludeFile = 0;
        $raw = $this->readHTMLStringFromFile($filepath);
        return $this->readHTMLRaw($raw);
    }


    public function readHTMLRaw(string $raw = ''): \DOMElement|\DOMNameSpaceNode|\DOMNode|null
    {
        if (!$raw)
            return null;
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($raw);
        libxml_clear_errors();
        //$doc->documentElement
        return $doc->getElementsByTagName('html')->item(0);
    }

    public function save(
        array  $statements = [],
        array  $aliasStatements = [],
        string $fileNamePHP = 'render.php',
        string $namespace = ''
    ): void
    {
        file_put_contents(
            $fileNamePHP,
            "<?php\n" . $this->toString(
                $statements,
                $aliasStatements,
                $fileNamePHP,
                $namespace
            )
        );
        chmod($fileNamePHP, 0777);

    }

    public function view(array $cmds = []): void
    {
        if (php_sapi_name() === 'cli')
            $newline = "\n";
        else
            $newline = '<br />';
        foreach ($cmds as $value) {
            echo $value . $newline;
        }
    }


    public function toString(
        array  $statements = [],
        array  $aliasStatements = [],
        string $phpfile = 'render.php',
        string $namespace = ''
    ): string
    {

        $result = '';
        $newline = "\n";
        //Namespace
        if ($namespace)
            $result .= "namespace {$namespace};" . $newline;
        // --- Autoload --- //
        //    coming soon   //
        // ---    end   --- //
        //Use Tag
        $usenamespace = "use \\Yukanoe\\HTML\\Tag;";
        $result .= $usenamespace . $newline;
        //Docs Header
        $doc = "//FileSave: $phpfile";
        $result .= $doc . $newline;
        //Main
        $result .= "//Main Here" . $newline;
        foreach ($statements as $value) {
            $result .= $value . $newline;
        }
        //Alias
        $result .= "//Alias Here" . $newline;
        foreach ($aliasStatements as $value) {
            $result .= $value . $newline;
        }
        return $result;
    }

}
