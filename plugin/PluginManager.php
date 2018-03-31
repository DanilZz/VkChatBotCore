<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

class PluginManager{
	
	public $plugins = array();
	
	public function __construct($path, $bot){
		$this->path = $path;
		$this->bot = $bot;
		$bot->pm = $this;
	}
	
	//public function runCommand($cmd,$we,$wr,$message,$user,$mid){
	public function runCommand($player){
		$cmd = $player->getLastCommand();
		$we = $player->getChat()->getChatType();
		$wr = $player->getChat()->getChatId();
		$message = $player->getLastMessage();
		$user = $player->getId();
		$mid = $player->getLastMessageId();
		foreach($this->plugins as $plugin){
			$commands = $plugin->pconfig->get("commands");
			//var_dump($commands);
			//var_dump($cmd);
			if (is_callable(array($plugin, 'onCommand')) && isset($commands[$cmd])){
				$args = explode(" ",$message);
				if(count($args) == 1 || $args == null){
					$args = array();
				}else{
					array_shift($args);
				}
				$mm = $plugin->onCommand($player,$cmd,$args);
				if($mm != null) return $mm;
			}
		}
		return null;
	}
	
	public function loadPlugin($name){
		$fs = glob($this->path."*");
		foreach($fs as $f){
			if(file_exists($f."/plugin.json")){
				$c = new Config($f."/plugin.json");
				if($c->get("name") == $name){
					require $f."/src/".$c->get("main").".php";
					$ex = explode("/",$c->get("main"));
					$class = array_pop($ex);
					$plugin = new $class;
					$this->getBot()->getLogger()->info("Загружен плагин ".$c->get("name"));
					$plugin->bot = $this->getBot();
					$plugin->pconfig = $c;
					$plugin->onEnable();
					$this->plugins[$c->get("name")] = $plugin;
					//return $plugin;
				}else{
					//return null;
				}
			}
		}
	}
	
	public function loadPlugins(){
		$fs = glob($this->path."*");
		foreach($fs as $f){
			$c = new Config($f."/plugin.json");
			$this->loadPlugin($c->get("name"));
		}
	}
	
	public function disablePlugin($name){
		$fs = glob($this->path."*");
		foreach($fs as $f){
			$c = new Config($f."/plugin.json");
			if($c->get("name") == $name){
				$plugin = $this->plugins[$c->get("name")];
				$this->getBot()->getLogger()->info("Выключен плагин ".$c->get("name"));
				$plugin->onDisable();
				unset($this->plugins[$c->get("name")]);
				return true;
			}else{
				continue;
			}
		}
		return false;
	}
	
	public function getBot(){
		return $this->bot;
	}
	
}
