<?php
/**
 * A Browser Object that contains information about the requesting browser
 * 
 * @author Johnathan Hebert <johnathan@jdcommerce.com>
 */
class Browser extends Object {
	
	public $userAgent;
	public $isOpera;
	public $isChrome;
	public $isWebKit;
	public $isSafari;
	public $isSafari2;
	public $isSafari3;
	public $isSafari4;
	public $isIE;
	public $isIE6;
	public $isIE7;
	public $isIE8;
	public $isGecko;
	public $isGecko2;
	public $isGecko3;
	public $isWindows;
	public $isMac;
	public $isAir;
	public $isLinux;
	
	public function __construct($userAgent) {
		$this->userAgent = $userAgent;
		$this->parseUserAgent();
	}
	
	/**
	 * Determine browser type, browser version, operating system, etc.
	 * 
	 * This logic is taken from the ExtJS Core javascript library at 
	 * http://www.extjs.com
	 */
	private function parseUserAgent() {
		$this->isOpera   = (bool)preg_match('/opera/i',$this->userAgent);
		$this->isChrome  = (bool)preg_match('/chrome/i',$this->userAgent);
		$this->isWebKit  = (bool)preg_match('/webkit/i',$this->userAgent);
		$this->isSafari  = !$this->isChrome && (bool)preg_match('/safari/i',$this->userAgent);
		$this->isSafari2 = $this->isSafari && (bool)preg_match('/applewebkit\/4/i',$this->userAgent);
		$this->isSafari3 = $this->isSafari && (bool)preg_match('/version\/3/i',$this->userAgent);
		$this->isSafari4 = $this->isSafari && (bool)preg_match('/version\/4/i',$this->userAgent);
		$this->isIE      = !$this->isOpera && (bool)preg_match('/msie/i',$this->userAgent);
		$this->isIE7     = $this->isIE && (bool)preg_match('/msie 7/i',$this->userAgent);
		$this->isIE8     = $this->isIE && (bool)preg_match('/msie 8/i',$this->userAgent);
		$this->isIE6     = $this->isIE && !$this->isIE7 && !$this->isIE8;
		$this->isGecko   = !$this->isWebKit && (bool)preg_match('/gecko/i',$this->userAgent);
		$this->isGecko2  = $this->isGecko && (bool)preg_match('/rv:1\.8/i',$this->userAgent);
		$this->isGecko3  = $this->isGecko && (bool)preg_match('/rv:1\.9/i',$this->userAgent);
		$this->isWindows = (bool)preg_match('/windows|win32/i',$this->userAgent);
		$this->isMac     = (bool)preg_match('/macintosh|mac os x/i',$this->userAgent);
		$this->isAir     = (bool)preg_match('/adobeair/i',$this->userAgent);
		$this->isLinux   = (bool)preg_match('/linux/i',$this->userAgent);
	}
	
}
?>