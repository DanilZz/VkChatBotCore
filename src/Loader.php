<?php

/*class A extends \Thread{
	public function run($bot,$log){
		while(true){
			$cmd = trim(fgets(STDIN));
			if($cmd == "stop"){
				$log->info("Выключение бота");
				$bot->getRunner()->stop();
			}else{
				if($cmd != null) $log->info("Неизвестная команда");
			}
		}
	}
}*/

$loading = microtime();

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'Bot.php';
require 'commands/CommandController.php';
require 'Runner.php';
require 'plugin/PluginManager.php';
require 'utils/Config.php';
require 'network/Network.php';
require 'task/TaskManager.php';
require 'chat/Chat.php';
require 'chat/ChatManager.php';
require 'chat/player/Player.php';
require 'chat/player/PlayerManager.php';
require_once 'utils/Logger.php';

$mainFolder = dirname(__DIR__);

if(!file_exists($mainFolder."/logs/")) mkdir($mainFolder."/logs/");
if(!file_exists($mainFolder."/db/")) mkdir($mainFolder."/db/");
if(!file_exists($mainFolder."/plugins/")) mkdir($mainFolder."/plugins/");
if(!file_exists($mainFolder."/users/")) mkdir($mainFolder."/users/");

$cache = new Config($mainFolder."/cache.json", true);

$cache->set("mem",memory_get_usage());

$log = file_get_contents($mainFolder."/bot.log");
$st = explode("\n", $log);
$arh = false;
if(count($st) >= 1000){
	$number = $cache->get("num_log");
	file_put_contents($mainFolder."/logs/".$number.".bot.log",$log);
	$number++;
	$cache->set("num_log", $number);
	file_put_contents($mainFolder."/bot.log","");
	$arh = true;
}
$log = new Logger($mainFolder."/bot.log");

$log->info("Бот включается...");

if($arh == true) $log->info("Архивирован ".($number - 1).".bot.log успешно");

$bot_config = new Config($mainFolder."/bot.json");

$token = $bot_config->get("token");

$runner = new Runner;

$bot = new Bot($token, $runner);

$bot->mainFolder = $mainFolder;

$taskManager = new TaskManager($bot);

$bot->taskManager = $taskManager;

$bot->chatManager = new ChatManager($bot);

$bot->config = $bot_config;

$bot->cache = $cache;

$runner->bot = $bot;

$log->info("Включение плагинов");

$pm = new PluginManager($mainFolder."/plugins/", $bot);

$pm->loadPlugins();

$log->info("Плагины включены");

$cmd = new CommandController;

$cmd->bot = $bot;

$bot->cmd = $cmd;

$count = count($cmd->loadCommands());

$log->info("Загружено ".$count." команд");

$log->info("Бот включен (".round((microtime() - $loading),3).")");

$bot->getRunner()->start();

/*$a = new A;
$a->run($bot,$log);*/

//shell_exec("php ".$mainFolder."/src/Console.php");

$bot->getRunner()->run("https://api.vk.com/method/",5.69,$token,$cmd,$log);

/*class A extends \Thread{
	public function run($bot,$log){
		while(true){
			$cmd = trim(fgets(STDIN));
			if($cmd == "stop"){
				$log->info("Выключение бота");
				$bot->getRunner()->stop();
			}else{
				if($cmd != null) $log->info("Неизвестная команда");
			}
		}
	}
}*/
