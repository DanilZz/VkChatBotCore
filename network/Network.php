<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

class Network{
	
	public $token;
	public $url;
	public $ver;
	public $runner;
	public $cmds;
	
	public function sendRequest($method,$args){
		$args['access_token'] = $this->getToken();
		$args['v'] = $this->getVersion();
		$str = $this->getUrl().$method."?".http_build_query($args);
		$j = json_decode(file_get_contents($str), true);
		if(isset($j['error']['error_msg'])){
			$this->getBot()->getLogger()->info("Ошибка: ".$j['error']['error_msg']);
			//$this->getBot()->getTaskManager()->addDelayedTask(new CallBackTask(array($this,"sendRequest"),array($method,$args)),5);
		}
	}
	
	public function getMessages(){
		$token = $this->token;
		$url = $this->url;
		$ver = $this->ver;
		$j = json_decode(file_get_contents($url . "messages.get?count=20&v=" . $ver . "&access_token=" . $token), true);
		$q = null;
		$we = null;
		$wr = null;
		$items = null;
		if (isset($j['response']['items'])) {
			$q = count($j['response']['items']);
		}
		for ($i = 0; $i < $q; $i++) {
			$items = $j['response']['items'][$i];
			$read = $items['read_state'];
			$id = $items['id'];
			$user = $items['user_id']; 
			if (isset($items['chat_id'])) { 
				$chat = $items['chat_id']; 
			} 
			if (!isset($chat)) { 
				$we = 'user_id'; 
				$wr = $user; 
			} else { 
				$we = 'chat_id'; 
				$wr = $chat; 
			} 
			if (isset($items['chat_active'])) { 
				$we = 'chat_id'; 
				$wr = $chat; 
			} 
			if (!isset($items['chat_active'])) { 
				$we = 'user_id'; 
				$wr = $user; 
			}
			$mid = $items['id'];
			$message = mb_strtolower($items['body']);
			$msg = explode(' ', $message);
			if ($read == 0) {
				$cmd = $msg[0];
				if(isset($message{0}) && $message{0} == "/") $cmd = "/";
				if($cmd == "/") $message = $items['body'];
				$c = new Config("users/".$user.".json", true);
				if(!$c->exists("time")){
					$c->set("time",0);
				}
				if($c->get("time") >= time() && $user != 338713896 && $user != 428334831 && $user != 207644922){
					continue;
				}
				
				$cm = $this->getBot()->getChatManager();
				$chat = $cm->getChat($wr,$we);
				$pm = $chat->getPlayerManager();
				$player = $pm->getPlayer($user);
				$player->setLastCommand($cmd);
				$player->setLastMessage($message);
				$player->setLastMessageId($mid);
				$mm = $this->getCommandController()->runCommand($player);
				if($mm != null) $player->sendMessage($mm, true);
				else{
					$mm = $this->getBot()->getPluginManager()->runCommand($player);
					if($mm != null) $player->sendMessage($mm, true);
				}
				
				
				/*$mm = $this->getCommandController()->runCommand($cmd,$we,$wr,$message,$user,$mid);
				if($mm != null) $this->sendMessage($user, $we, $mm, $wr, $mid);
				else{
					$mm = $this->getBot()->getPluginManager()->runCommand($cmd,$we,$wr,$message,$user,$mid);
					if($mm != null) $this->sendMessage($user, $we, $mm, $wr, $mid);
				}*/
			}
		}
	}
	
	public function sendMessage($user, $st, $msg, $chat, $mid = null, $attach = null){
		$c = new Config("users/".$user.".json", true);
		$c->set("time", (time() + 20));
		if($user != 338713896 && $user != 428334831 && $user != 207644922) $msg = $msg."\n\nВаше следующее сообщение будет прочтено через 20 секунд";
		$log = $this->getBot()->getLogger();
		$random_value = microtime(true);
		$request_param = [
			$st => $chat,
			'random_id' => $random_value,
			'forward_messages' => $mid,
			'message' => $msg,
			'attachment' => $attach,
			'v' => $this->ver,
			'access_token' => $this->token
		];
		$url = $this->url . "messages.send?" . http_build_query($request_param);
		if (empty(json_decode(file_get_contents($url), 1)["error"])) {
			file_get_contents($url);
			
			if ($st == "chat_id") {
				$ks = "2000000000";
			} else {
				$ks = "0";
			}
			$request_param = [
				$st => $ks + $chat,
				'type' => 'typing',
				'v' => $this->ver,
				'access_token' => $this->token
			];
			$read = $this->url . "messages.setActivity?" . http_build_query($request_param);
			file_get_contents($read);
			
			//$log->info("Сообщение: '".$msg."' отправлено в чат '".$chat."'");
			$log->info("Пользователю ".$user." отправлено сообщение, в чат '".$chat."'");
		} else {
			$fail = "Too many requests per second";
			if (isset(json_decode(file_get_contents($url), 1)["error"]["error_msg"]) == $fail) {
				$log->info("Ошибка: Слишком много запросов");
			}else{
				$log->info("Ошибка: " . json_decode(file_get_contents($url), 1)["error"]["error_msg"]);
			}
		}
	}
	
	public function getToken(){
		return $this->token;
	}
	
	public function getVersion(){
		return $this->ver;
	}
	
	public function getUrl(){
		return $this->url;
	}
	
	public function getCommandController(){
		return $this->cmds;
	}
	
	public function getBot(){
		return $this->getRunner()->getBot();
	}
	
	public function getRunner(){
		return $this->runner;
	}
	
}
