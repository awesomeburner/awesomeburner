<?php
class post {
	public function create() {}
	public function view($post_id) {}
	public function delete($post_id, $feed_id) {}

	public function get_item($intItemID) {
		$strQuery = "SELECT * FROM `feed_items` WHERE `item_id`={$intItemID}";
		$doQuery = mysql_query($strQuery);
		
		if (mysql_error() <> "") {
			echo mysql_error()."<br>".$strQuery;
		}

		$res = mysql_fetch_object($doQuery);

		$item = new container_item($res->item_id, $res->feed_id, $res->pubdate_int, $res->title, $res->link, $res->description, $res->author, $res->category, $res->comments, $res->enclousure, $res->guid, $res->pubdate, $res->source);
		
		return $item;
	}
}