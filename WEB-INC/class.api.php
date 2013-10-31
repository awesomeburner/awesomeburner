<?php
class clsApi extends clsMysql {
	public $feed;
	public $item;
	public $keyword;
	public $stat;

	function __call($method, $args) {
		$methods = array('feed' => true, 'stats' => true, 'crawler' => true);

		if (!isset($methods[$method])) {
			return array("status" => array("code" => 2, "message" => "method not exists"));
		}

		$clsname = 'agregator_'.$method;

		if (!file_exists("../WEB-INC/class.{$method}.php")) {
			return array("status" => array("code" => 3, "message" => "method class not exists"));
		}

		include "../WEB-INC/class.{$method}.php";

		$$clsname = new $clsname();

		return $$clsname->$args[0]($args[1]);
	}
}
