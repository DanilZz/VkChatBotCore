<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

class TestCommand{
	
	public $bot;
	
	public function onLoad(){
		$this->getBot()->getLogger()->info("Команда test загружена");
	}
	
	public function onCommand($player,$args){
		$player->sendMessage("test!", true);
	}
	
	public function getBot(){
		return $this->bot;
	}
	
}
