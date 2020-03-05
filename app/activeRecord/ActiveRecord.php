<?php
/**
 * Created on Mar 5, 2020
 * @author Alexandre Bezerra Barbosa
 * @email alxbbarbosa@yahoo.com.br
 * @author Gabriel Assuero
 * @email gabrielassuerors@gmail.com
 * Version 1.0 final
 */

/**

	ActiveRecord return json 
	Configure $tableRows

*/

abstract class ActiveRecord {

	private $content;
	private $tableRows;
	protected $idField;
	protected $table;
	protected $logTimestamp;

	public function __construct(){
		if(empty($this->tableRows)){

			echo "<pre>$tableRows is null! insert rows nunber manually</pre>";
			$this->tableRows = NULL;
		}
		if(!is_bool($this->logTimestamp)){

			$this->logTimestamp = TRUE;
		}
		if($this->table == NULL){

			$this->table = strtolower(get_class($this));
		}
		if($this->idField == NULL){

			$this->idField  = 'id';
		}
	}

	public function __set($key, $value){

		$this->$content[$key] == $value;	
	}

	public function __get($key){

		return $this->content[$key];
	}

	public function __isset($key){

		return isset($this->content[$key]);
	}

	public function __unset($key){

		if(isset($key){
			unset($this->content[$key]);
			return TRUE;
		}
		return FALSE;

	}

	public function __clone(){

		if(isset($this->content[$this->idField])){
			unset($this->content[$this->idField]);
		}

	}

	// Array to content array id is not computed

	public function getContent(){

		return $this->content;
	}

	public function setContent(array $array){

		$this->content = $array;
	}

	// Transform array to json 

	public function getJson(){

		return json_encode($this->content);
	}

	public function setJson(string $str){

		$this->content = json_decode($str);
	}

	//Suport function

	private function format($value){

		switch ($value) {
			case is_string($value) && !empty($value):
				return "'". addcslashes($value) . "'"
				break;

			case is_bool($value):
				return $value ? 'TRUE' : 'FALSE';
				break;

			case $value !=='':
				return $value;
				break;
			
			default:
				return 'NULL';
				break;
		}

	}

	private function cvtContent(){

		$newContent = array();
		foreach ($this->content as $key => $value) {
			if (is_scalar($value)) {
				$newContent[$key] = $this->format($value);
			}
		}

	}

	//CRUD 

	protected function save(){

		$newContent = $this->convertContent();
 
	    if (isset($this->content[$this->idField])) {

	        $sets = array();
	        foreach ($newContent as $key => $value) {

	            if ($key === $this->idField)
	                continue;
	            $sets[] = "{$key} = {$value}";
	        }

	        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$this->id} = {$this->content[$this->idField]};";

	    } else {

	        $sql = "INSERT INTO {$this->table} (" . implode(', ', array_keys($newContent)) . ') VALUES (' . implode(',', array_values($newContent)) . ');';
	    }
	    echo $sql;

	}



}