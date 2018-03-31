<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

class Chat{
	
	public $bot;
	
	public function __construct($cm,$bot,$type,$cid){
		$this->bot = $bot;
		$this->lastTime = time();
		$this->type = $type;
		$this->cid = $cid;
		$this->cm = $cm;
		$this->pm = new PlayerManager($this,$this->getBot());
	}
	
	public function getPlayerManager(){
		return $this->pm;
	}
	
	public function getLastTime(){
		return $this->lastTime;
	}
	
	public function getChatManager(){
		return $this->cm;
	}
	
	public function getBot(){
		return $this->bot;
	}
	
	public function getChatType(){
		return $this->type;
	}
	
	public function getChatId(){
		return $this->cid;
	}
	
	public function getCID(){
		return $this->cid;
	}
	
	
	
}
