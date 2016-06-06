<?php
/**
 * SCCM Log Viewer
 * @author Åsmund Stavdahl <asmund.stavdahl@itk.ntnu.no>
 */

class SCCM_Log_Entry {
	// Original line
	private $line;

	private $properties;

	function __construct($line){
		$this->line = $line;
		$this->properties = (object) [];
		$this->parse();
	}

	private function parse(){
		$patterns = [
			"title"	   	=> '_<!\[LOG\[(.*)\]LOG\]!>_',
			"time"	   	=> '_time="([^"]*)"_',
			"date"	   	=> '_date="([^"]*)"_',
			"component"	=> '_component="([^"]*)"_',
			"context"	=> '_context="([^"]*)"_',
			"type"	   	=> '_type="([^"]*)"_',
			"thread"	=> '_thread="([^"]*)"_',
			"file"	   	=> '_file="([^"]*)">_'
		];
		foreach($patterns as $name => $pattern){
			$matches = [];
			preg_match($pattern, $this->line, $matches);
			if(isset($matches[1])){
				$this->properties->{$name} = $matches[1];
			}
		}
	}

	public function __get($name){
		$ret = @$this->properties->{$name};
		return $ret ?htmlentities($ret) :"<i>[empty]</i>";
	}
}
