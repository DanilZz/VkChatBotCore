<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);
$time = time() + 10;
while(true){
	$cmd = trim(fgets(STDIN));
	file_put_contents(dirname(__DIR__)."/cmd.txt",$cmd);
	if($cmd == "stop"){
		exit();
	}
	if($time < time()){
		exit();
	}
}
