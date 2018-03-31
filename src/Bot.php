<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'utils/Logger.php';
require 'task/CallBackTask.php';

class Bot{
	
	public $pm;
	public $cmd;
	public $cache;
	public $config;
	public $mainFolder;
	
	public function __construct($token, $runner){
		$this->runner = $runner;
		$this->token = $token;
		$this->logger = new Logger("./bot.log");
	}
	
	public function getChatManager(){
		return $this->chatManager;
	}
	
	public function getRunner(){
		return $this->runner;
	}
	
	public function getToken(){
		return $this->token;
	}
	
	public function getLogger(){
		return $this->logger;
	}
	
	public function getCommandController(){
		return $this->cmd;
	}
	
	public function getPluginManager(){
		return $this->pm;
	}
	
	public function getCache(){
		return $this->cache;
	}
	
	public function getConfig(){
		return $this->config;
	}
	
	public function getNetwork(){
		return $this->network;
	}
	
	public function getTaskManager(){
		return $this->taskManager;
	}
	
	public function getDataPath(){
		return $this->mainFolder;
	}
	
}
