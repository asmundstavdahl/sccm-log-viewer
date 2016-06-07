<?php
/**
 * SCCM Log Viewer
 * @author Åsmund Stavdahl <asmund.stavdahl@itk.ntnu.no>
 */

/**
 * A instance of this class represents a single log entry in an SCCM log file.
 * 
 * It extracts information from a singe line of the log file.
 * 
 * @example
 * <code>
 * $logLines = file($logFileName);
 * $firstLogEntry = new SCCM_Log_Entry($logLines[0]);
 * # Print the timestamp of the log entry:
 * echo $firstLogEntry->time;
 * </code>
 */
class SCCM_Log_Entry {
	// Original line
	private $line;

	private $properties;

	/**
	 * @param string $line the log file line to extract information from
	 */
	function __construct($line){
		$this->line = $line;
		$this->properties = (object) [];
		$this->parse();
	}

	/**
	 * Extract informasjon from our line string.
	 */
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

	/**
	 * Getter for extracted information.
	 * @param  string $name name of information to get
	 * @return string       value of requested information
	 */
	public function __get($name){
		$ret = @$this->properties->{$name};
		return $ret ?htmlentities($ret) :"<i>[empty]</i>";
	}
}
