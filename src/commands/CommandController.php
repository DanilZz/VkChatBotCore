<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'CommandMap.php';

class CommandController{
	
	public function loadCommands(){
		$cm = new CommandMap($this->bot);
		$cms = $cm->getCommands();
		$this->commands = $cms;
		return $cms;
	}
	
	public function runCommand($player){
		$cmd = $player->getLastCommand();
		$message = $player->getLastMessage();
		if(in_array($cmd, array_keys($this->commands))){
			$c = $this->commands[$cmd];
			$args = explode(" ",$message);
			if(count($args) == 1 || $args == null){
				$args = array();
			}else{
				array_shift($args);
			}
			return $c->onCommand($player, $args);
		}else{
			return null;
		}
	}
	
	public function getBot(){
		return $this->bot;
	}
	
}
