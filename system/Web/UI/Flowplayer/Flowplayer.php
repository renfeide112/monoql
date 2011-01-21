<?php
class Flowplayer extends Object
{
	public $id;
	public $style;
	public $class;
	public $config = array();
	public $events = array();
	public $markup;

	public function __construct($config, $id=null, $style=null, $class=null)
	{
		$this->id = alt($id, uniqid());
		$this->style = alt($style, "width:480px;height:270px;");
		$this->class = $class;
		
		if (is_array($config)) {
			$this->config = $config;
		} else {
			$this->config["clip"]["url"] = $config;
		}
		
		$this->config["plugins"]["controls"]["url"] = HTML::url("/_shared/flowplayer/3.2.0/flowplayer.controls.swf");
		$this->config["plugins"]["pseudo"]["url"] = HTML::url("/_shared/flowplayer/3.2.1/flowplayer.pseudostreaming.swf");
	}

	public function getConfigString()
	{
		foreach ($this->config as $key=>$value) {
			if (preg_match('/^on[A-Z]/', $key)) {
				$this->events[$key] = $value;
				unset($this->config[$key]);
			}
		}
		return json_encode($this->config);
	}

	public function render()
	{
		$markup = "<div id=\"{$this->id}\" class=\"{$this->class}\" style=\"{$this->style}\">";
		$markup .= $this->markup;
		$markup .= "</div>";
		$markup .= "<script type=\"text/javascript\">";
		$markup .= "flowplayer('{$this->id}', {src:'" . HTML::url("/_shared/flowplayer/3.2.1/flowplayer.swf") . "'}, {$this->getConfigString()});";
		foreach ($this->events as $method=>$param) {
			$markup .= "flowplayer('{$this->id}').{$method}({$param});";
		}
		$markup .= "</script>";
		return $markup;
	}

	public function __toString()
	{
		return $this->render();
	}
	
	public static function show($config, $id=null, $style=null, $class=null) {
		$flowplayer = new Flowplayer($config, $id, $style, $class);
		echo $flowplayer->render();
	}
}
?>
