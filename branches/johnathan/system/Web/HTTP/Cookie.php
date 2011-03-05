<?php
class Cookie extends Object {
	public $name;
	public $value;
	public $expire;
	public $path;
	public $domain;
	public $secure;
	public $httpOnly;
	
	public function __construct($name, $value="", $expire=0, $path="", $domain="", $secure=false, $httpOnly=false) {
		parent::__construct();
		$this->name = $name;
		$this->value = $value;
		$this->expire = $expire;
		$this->path = $path;
		$this->domain = $domain;
		$this->secure = $secure;
		$this->httpOnly = $httpOnly;
	}
	
	public function addToResponse() {
		Response::setCookie($this->name,$this->value,$this->expire,$this->path,$this->domain,$this->secure,$this->httpOnly);
		return self;
	}
}
?>