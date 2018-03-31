<?php

class Config{
	
	public $path;
	public $db;
	public $autosave;
	
	public function __construct($path, $autosave = false){
		$this->path = $path;
		$this->autosave = $autosave;
		if(!file_exists($path)) file_put_contents($path, json_encode([]));
		$this->db = json_decode(file_get_contents($path),true);
	}
	
	public function get($name){
		if(isset($this->db[$name])) return $this->db[$name];
		else return null;
	}
	
	public function set($name, $value){
		$this->db[$name] = $value;
		if($this->autosave == true) $this->save();
		return true;
	}
	
	public function exists($name){
		if(isset($this->db[$name])) return true;
		else return null;
	}
	
	public function getAll(){
		return $this->db;
	}
	
	public function setAll($data){
		$this->db = $data;
		if($this->autosave == true) $this->save();
		return true;
	}
	
	public function save(){
		file_put_contents($this->path, json_encode($this->db, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE));
	}
	
}
