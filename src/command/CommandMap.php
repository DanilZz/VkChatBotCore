<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'TestCommand.php';

class CommandMap{
	
	public function __construct($bot){
		$this->bot = $bot;
		$this->registerCommands();
	}
	
	public function registerCommands(){
		$cmds = [];
		$cmds['test'] = new TestCommand;
		$this->cmds = $cmds;
		$this->setCommandsBot();
		$this->onLoadCommands();
	}
	
	public function onLoadCommands(){
		foreach($this->cmds as $cmd){
			if(is_callable(array($cmd, "onLoad"))){
				$cmd->onLoad();
			}
		}
	}
	
	public function registerCommand($command, $class){
		$this->cmds[$command] = $class;
		$this->setCommandBot($class);
	}
	
	public function unRegisterCommand($command){
		unset($this->cmds[$command]);
	}
	
	public function setCommandsBot(){
		foreach($this->cmds as $cmd){
			$this->setCommandBot($cmd);
		}
	}
	
	public function setCommandBot($cmd){
		$cmd->bot = $this->bot;
	}
	
	public function getCommands(){
		return $this->cmds;
	}
	
	public function getBot(){
		return $this->bot;
	}
	
}
