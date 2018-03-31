<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require "TextFormat.php";

class Logger{
	
	public function __construct($path){
		$this->path = $path;
		$this->tf = new TextFormat;
	}
	 
	public function info($m){
		$tf = $this->tf;
		$str = file_get_contents($this->path);
		$now = time();
		$str2 = $tf->AQUA."[".date("H:i:s", $now)."]".$tf->GOLD." | ".$tf->WHITE.$m;
		$c = $str2.$tf->RESET."\n";
		$this->toConsole($c);
		$str2 = "[".date("H:i:s", $now)."] | ".$m;
		$str_new = $str."\n".$str2;
		$this->toLog($str_new);
	}
	
	public function toConsole($m){
		print_r($m);
	}
	
	public function toLog($m){
		file_put_contents($this->path,$m);
	}
	
}
