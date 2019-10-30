<?php

class unityPage extends pageCreator {
	public function __construct() {
		parent::__construct();
		$this->css = array("/css/global.css");
		$this->js = array("/js/global.js");
		$this->meta = array("charset='UTF-8'","name='viewport' content='width=device-width, initial-scale=1.0'");
		$this->header = array(CONFIG["paths"]["templates"] . "/navbar.php");
		$this->footer = array(CONFIG["paths"]["templates"] . "/footer.php");
	}

	public static function authUser($userEntry, $action = true) {
		if (!$userEntry->exists() || ($userEntry->getAttribute("description") != NULL && (in_array("PENDING-PI", $userEntry->getAttribute("description")) || in_array("PENDING-ADMIN", $userEntry->getAttribute("description"))))) {
			if ($action) {
				header("Location: /not_authorized.php");
			  die("403");
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
}

class pageCreator
{
	protected $css;
	protected $js;
	protected $meta;
	protected $title;
	protected $header;
	protected $footer;
	protected $content;

	public function __construct()
	{
		$this->css = array();
		$this->js = array();
		$this->meta = array();
		$this->title = "";
		$this->header = array();
		$this->content = array();
		$this->footer = array();
	}

	public function addCSS($path)
	{
		array_push($this->css, $path);
	}

	public function addJS($path)
	{
		array_push($this->js, $path);
	}

	public function addMeta($content)
	{
		array_push($this->meta, $content);
	}

	public function addHeaderContent($path)
	{
		array_push($this->header, $path);
	}

	public function addContent($path) {
		array_push($this->content, $path);
	}

	public function addFooterContent($path)
	{
		array_unshift($this->footer, $path);
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getHeader($includeContent = true)
	{
		$out = "<html>";
		$out .= "<head>";

		// Meta Tags
		foreach ($this->meta as $item) {
			$out .= "<meta " . $item . ">";
		}

		// CSS
		foreach ($this->css as $item) {
			$out .= "<link rel='stylesheet' type='text/css' href='$item'>";
		}

		// JS
		foreach ($this->js as $item) {
			$out .= "<script src='$item'></script>";
		}

		// Title
		$out .= "<title>$this->title</title>";

		$out .= "</head>";
		$out .= "<body>";

		foreach ($this->header as $item) {
			ob_start();
			include $item;
			$out .= ob_get_clean();
		}

		$out .= "<div id='main_wrapper'>";

		if ($includeContent) {
			$out .= $this->getContent();
		}

		return $out;
	}

	public function getContent() {
		$out = "";
		foreach ($this->content as $item) {
			ob_start();
			include $item;
			$out .= ob_get_clean();
		}

		return $out;
	}

	public function getFooter()
	{
		$out = "</div>";

		foreach ($this->footer as $item) {
			ob_start();
			include $item;
			$out .= ob_get_clean();
		}

		$out .= "</body>";
		$out .= "</html>";

		return $out;
	}
}
