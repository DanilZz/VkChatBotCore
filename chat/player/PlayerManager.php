<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

class PlayerManager{
	
	public $players = array();
	
	public function __construct($chat, $bot){
		$this->bot = $bot;
		$this->chat = $chat;
	}
	
	public function addPlayer($id){
		$player = new Player($id, $this->getBot(), $this->getChat());
		$this->players[$id] = $player;
		return $player;
	}
	
	public function getPlayer($id){
		if(isset($this->getPlayers()[$id])) return $this->getPlayers()[$id];
		else return $this->addPlayer($id);
	}
	
	public function removePlayer($id){
		unset($this->players[$id]);
	}
	
	public function checkPlayers(){
		foreach($this->getPlayers() as $player){
			if(($player->getLastTime() + 60) < time()){
				$this->removePlayer($player->getId());
			}
		}
	}
	
	public function getPlayers(){
		return $this->players;
	}
	
	public function getChat(){
		return $this->chat;
	}
	
	public function getBot(){
		return $this->bot;
	}
	
}
