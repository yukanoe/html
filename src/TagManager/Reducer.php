<?php
namespace Yukanoe\HTML\TagManager;
/**
 * rewrite: 
 * {
 *      $YT_AV[10] = new \tag('div', ['class'=>'card'], NULL);
 *      $YT_AV[10]->text = 'UwU';
 * }
 * =>
 * {
 *     $YT_AV[10] = new \tag('div', ['class'=>'card'], 'UwU');
 * }
 * 
 * 
 */
class Reducer
{
	

	function __construct()
	{
		return $this;
	}

	public function reduce($InputStatements)
	{
		$regVarName = Compiler::$regVarName;
		$logger     = new Logger;
	    $baseStatements = []; // (new tag, addChild without ->Text='value')

	    foreach ($InputStatements as $line) {

	    	$logger->info("\n[INFO] read $line");

	        if( preg_match('/^(\$'.$regVarName.'\[[0-9]{0,9}\])\-\>text \= /', $line, $match) ){
	        	
	            $currentAV = $match[1] ?? 'Error';

	            $logger->info("\n[INFO] Found Text content is $currentAV");

	            foreach ($baseStatements as &$statement) {
	                //find parent: start with ~ /^$yut_av_xx = new tag(/
	                if( str_starts_with($statement, "$currentAV = new Tag(") ){
	                    //create inner
	                    $logger->info("\n - REDUCE:[ @$line + @$statement => ");
	                    //get Text content
	                    $Offset = strlen($currentAV)+strlen('->Text = ');
	                    $Text   = substr($line, $Offset, -1);
	                    //regex replace /, NULL)$/ by Text content
	                    $statement = preg_replace(
	                    	'/\, \'\'\)\;$/',
	                    	", $Text);",
	                    	$statement
	                    );

	                    $logger->info("$statement ]\n");

	                    break;
	                }
	            }
	            //echo "IS inner <br />";
	        }
	        else
	            array_push($baseStatements, $line);
	    }
	    
	    return $baseStatements;
	}

}