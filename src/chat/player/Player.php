<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

class Player{
	
	public $id;
	public $config;
	public $lastTime;
	public $bot;
	
	public function __construct($id, $bot, $chat){
		$this->bot = $bot;
		$this->chat = $chat;
		$this->id = $id;
		$this->lastTime = time();
		if(!file_exists($this->getBot()->getDataPath()."/users/".$this->getId().".json")){
			$this->getBot()->getLogger()->info("Зарегестрирован новый пользователь ID - ".$this->getId());
			//$this->getBot()->getLogger()->info("Создание нового профиля...");
		}
		$this->config = new Config($this->getBot()->getDataPath()."/users/".$this->getId().".json", true);
		$this->setAllValues();
	}
	
	public function setAllValues(){
		if(!$this->getConfig()->exists("money")){
			$this->getConfig()->set("money", 10000);
		}
		if(!$this->getConfig()->exists("time")){
			$this->getConfig()->set("time", 0);
		}
	}
	
	public function setLastCommand($cmd){
		$this->cmd = $cmd;
	}
	
	public function setLastMessage($message){
		$this->message = $message;
	}
	
	public function setLastMessageId($mid){
		$this->mid = $mid;
	}
	
	public function getLastCommand(){
		return $this->cmd;
	}
	
	public function getLastMessage(){
		return $this->message;
	}
	
	public function getLastMessageId(){
		return $this->mid;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getConfig(){
		return $this->config;
	}
	
	public function getChat(){
		return $this->chat;
	}
	
	public function getBot(){
		return $this->bot;
	}
	
	public function getLastTime(){
		return $this->lastTime;
	}
	
	public function sendMessage($msg,$sendMid = false,$attach = null){
		$this->getBot()->getLogger()->info("Пользователю '".$this->getId()."' было отправлено сообщение в чат '".$this->getChat()->getChatId()."'");
		if($sendMid == true){
			$mid = $this->getLastMessageId();
		}else{
			$mid = null;
		}
		$this->lastTime = time();
		$this->getChat()->lastTime = time();
		$chat = $this->getChat();
		$args = array();
		$args[$chat->getChatType()] = $chat->getChatId();
		if($mid != null) $args['forward_messages'] = $mid;
		$args['random_id'] = microtime(true);
		if($attach != null) $args['attachment'] = $attach;
		$args['message'] = $msg;
		$this->getBot()->getNetwork()->sendRequest("messages.send",$args);
	}
	
}
