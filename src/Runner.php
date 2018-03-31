<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

class Runner{
	
	public $enable = false;
	public $logger;
	
	public function start(){
		$this->enable = true;
	}
	
	public function stop(){
		$this->enable = false;
		exit();
	}
	
	public function run($url,$ver,$token,$cmds,$log){
		$nw = new Network;
		$nw->token = $token;
		$nw->runner = $this;
		$nw->url = $url;
		$nw->ver = $ver;
		$nw->cmds = $cmds;
		$this->getBot()->network = $nw;
		$this->nw = $nw;
		while ($this->enable) {
			$nw->getMessages();
			$this->getTaskManager()->runTasks();
			$this->getBot()->getChatManager()->checkChats();
		}
	}
	
	public function getBot(){
		return $this->bot;
	}
	
	public function getTaskManager(){
		return $this->getBot()->getTaskManager();
	}
}
