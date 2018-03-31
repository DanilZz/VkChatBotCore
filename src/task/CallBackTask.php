<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

class CallBackTask{
	
	public $callable;
	public $args;
	
	public $taskId;
	public $time;
	
	public $taskManager;
	
	public $repeat = false;
	
	public function __construct($function, $args = array()){
		if(is_callable($function) && is_array($args)){
			$this->callable = $function;
			$this->args = $args;
		}
	}
	
	public function setRepeat($true = true){
		$this->repeat = $true;
	}
	
	public function getCallable(){
		return $this->callable;
	}
	
	public function isRepeat(){
		if($this->repeat == true){
			return true;
		}else{
			return false;
		}
	}
	
	public function onRun(){
		call_user_func_array($this->callable, $this->args);
		if($this->isRepeat()){
			$this->getTaskManager()->addDelayedTask($this, $this->time);
		}else{
			$this->getTaskManager()->removeTask($this->taskId);
		}
	}
	
	public function cancel(){
		$this->getTaskManager()->removeTask($this->taskId);
	}
	
	public function getTaskManager(){
		return $this->taskManager;
	}
	
}
