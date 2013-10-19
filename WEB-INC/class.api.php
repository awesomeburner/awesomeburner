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

print_r($args);

#		return $$clsname->$args['action']($args['option']);
	}

	/**
	 *
	 * @param array $arrOption => str URL
	 */
	public function feed_add($parameter=null) {
		$this->Query("INSERT INTO `feed_feeds` (`feed_url`) VALUES ('{$parameter->url}')");
		return $this->insert_id;
	}
	private function feed_delete($parameter=null) {
		if (isset($parameter->feed_id)) {
			$this->Query("DELETE FROM `feed_feeds` WHERE (`feed_id`={$parameter->feed_id})");
		}
		if (isset($parameter->url)) {
			$this->Query("DELETE FROM `feed_feeds` WHERE (`feed_url`={$parameter->url})");
		}
		return 0;		
	}
	private function feed_get($parameter=null) {
		$arrOption->id;
		$arrOption->num;
		$arrOption->keywords;
	}
	
	public function feed_get_by_id($parameter=null) {
		if (!isset($option['where']['id'])) {
			return false;
		}
		$feedid = $option['where']['id'];
		$j['url'] = $this->Query("SELECT `feed_url` as `url` FROM `feed_feeds` WHERE `feed_id`={$feedid}");
		return $j;
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
