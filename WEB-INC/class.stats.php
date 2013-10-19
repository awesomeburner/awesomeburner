<?php
class agregator_stats extends clsMysql {
	/**
	 * 
	 */
	public function get($parameter = null) {
		$d = $this->Query("SELECT (SELECT COUNT(*) FROM feed_feeds) as feeds, (SELECT COUNT(*) FROM feed_items) as items");
		
		return array("result" => $d, "status" => array("status" => 0, "message" => "ok"));
	}
}
