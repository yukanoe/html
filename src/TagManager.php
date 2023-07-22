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


	function __construct()
	{
		$this->IO       = new IO;
		return $this;
	}

	public function read($v_auto)
	{
		$this->domDocument = $this->IO->readHTMLFile($v_auto);
		return $this;
	}
	public function readraw($v_auto)
	{
		$this->domDocument = $this->IO->readHTMLRaw($v_auto);
		return $this;
	}

	public function build($Reducing = true)
	{
		//compiling
		$Compiler       = new Compiler;
		$this->mainArr  = $Compiler->runBuildTool($this->domDocument);
		$this->aliasArr = $Compiler->getTagAlias();
		//checking
		if(!$Reducing)
			return $this;
		//reducing
		$Reducer        = new Reducer;
		$this->mainArr  = $Reducer->reduce($this->mainArr);
		return $this;
	}

	public function save($phpfile = 'render.php')
	{
		$this->IO->save($this->mainArr, $this->aliasArr, $phpfile);
		return $this;
	}

	public function get($phpfile = 'render.php')
	{
		return $this->IO->toString($this->mainArr, $this->aliasArr, $phpfile);
	}

	public function view()
	{
		$this->IO->view($this->mainArr);
		return $this;
	}

	public function configureVarName($index='av', $alias='avn')
	{
		if($index)
			Compiler::$regVarName = $index;
		if($alias)
			Compiler::$aliVarName = $alias;
		return $this;
	}

	public function autoBuild($indir = '.', $outdir = '.', $namespace='')
	{
		foreach (glob($indir.'/'."*.html") as $filename) {
			$fileread = $filename;
			$filesave = $outdir.'/'.basename($fileread, ".html").'.php';
		    if(filesize($fileread) > 0) {
		    	echo "\n[INFO] {$fileread} => {$filesave}";
		    	$this->read($fileread)->build()->save($filesave);
		    }
		}

	}


}

