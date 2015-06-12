<?php
	
	
	
class Test{
	public static $testman = 1;
	public $hello_text;
	
	public function __construct($message){
		$this->hello_text = $message;
	}
	
	public function hello(){
		echo "hello world_".$this->hello_text;
		
	}
	
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
?>