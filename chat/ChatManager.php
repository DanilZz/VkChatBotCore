<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

class ChatManager{
	
	public $chats = array();
	
	public function __construct($bot){
		$this->bot = $bot;
	}
	
	public function addChat($cid,$type){
		$chat = new Chat($this,$this->getBot(),$type,$cid);
		$this->chats[$cid] = $chat;
		return $chat;
	}
	
	public function getChat($cid,$type){
		if(isset($this->getchats()[$cid])) return $this->getchats()[$cid];
		else return $this->addChat($cid,$type);
	}
	
	public function removeChat($cid){
		unset($this->chats[$cid]);
	}
	
	public function checkChats(){
		foreach($this->getChats() as $chat){
			$chat->getPlayerManager()->checkPlayers();
			if(($chat->getLastTime() + 60) < time()){
				$this->removeChat($chat->getChatId());
			}
		}
	}
	
	public function getChats(){
		return $this->chats;
	}
	
	public function getBot(){
		return $this->bot;
	}
	
}
