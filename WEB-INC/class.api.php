<?php
class clsApi extends clsMysql {
	public $feed;
	public $item;
	public $keyword;
	public $stat;

	function __call($method, $args) {
		$methods = array('feed' => true, 'stats');

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

	public function keyword_get($parameter=null) {
		return $this->Query("SELECT * FROM `feed_keywords`", true);
	}
	public function keyword_getKeywordId($arrOption) {}
	public function keyword_add($parameter=null) {}



	/**
	 * 
	 */
	public function stat_get($parameter=null) {
		switch ($parameter->object) {
			// общее количество rss-лент в базе
			case 'total_feeds':
				$j['total_feeds'] = $this->Query("SELECT COUNT(*) FROM `feed_feeds`");
			break;
			// общее количество статей
			case 'total_items':
				$j['total_items'] = $this->Query("SELECT COUNT(*) FROM `feed_items`");
			break;
		}
		
		return $j;
	}

}
