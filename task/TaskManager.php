<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

class TaskManager{
	
	public $tasks = [];
	
	public function __construct($bot){
		$this->bot = $bot;
	}
	
	public function addDelayedTask($task, $time){
		$taskId = count($this->tasks);
		$this->tasks[$taskId] = $task;
		$task->taskId = $taskId;
		$task->taskManager = $this;
		$task->time = (time() + $time);
	}
	
	public function addRepeatingTask($task, $time){
		$taskId = count($this->tasks);
		$task->setRepeat(true);
		$this->tasks[$taskId] = $task;
		$task->taskId = $taskId;
		$task->time = (time() + $time);
	}
	
	public function runTasks(){
		foreach($this->tasks as $taskId => $task){
			if(time() >= $task->time){
				$task->onRun();
			}
		}
	}
	
	public function removeTask($taskId){
		unset($this->tasks[$taskId]);
	}
	
	public function getBot(){
		return $this->bot;
	}
	
}
