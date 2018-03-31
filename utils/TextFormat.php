<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

class TextFormat{
	
	public $BOLD = "\x1b[1m";
	public $OBFUSCATED = "";
	public $ITALIC = "\x1b[3m";
	public $UNDERLINE = "\x1b[4m";
	public $STRIKETHROUGH = "\x1b[9m";
	public $RESET = "\x1b[m";
	public $BLACK = "\x1b[38;5;16m";
	public $DARK_BLUE = "\x1b[38;5;19m";
	public $DARK_GREEN = "\x1b[38;5;34m";
	public $DARK_AQUA = "\x1b[38;5;37m";
	public $DARK_RED = "\x1b[38;5;124m";
	public $PURPLE = "\x1b[38;5;127m";
	public $GOLD = "\x1b[38;5;214m";
	public $GRAY = "\x1b[38;5;145m";
	public $DARK_GRAY = "\x1b[38;5;59m";
	public $BLUE = "\x1b[38;5;63m";
	public $GREEN = "\x1b[38;5;83m";
	public $AQUA = "\x1b[38;5;87m";
	public $RED = "\x1b[38;5;203m";
	public $LIGHT_PURPLE = "\x1b[38;5;207m";
	public $YELLOW = "\x1b[38;5;227m";
	public $WHITE = "\x1b[38;5;231m";
	
}
