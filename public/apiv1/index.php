<?php
// index.php

include '../WEB-INC/conf.php';
include '../WEB-INC/class.mysql.php';
include '../WEB-INC/class.api.php';

$api = new clsApi();

$method = (isset($_GET['method'])) ? strtolower(trim($_GET['method'])) : null;
$parameter = (isset($_GET['parameter'])) ? strtolower(trim($_GET['parameter'])) : null;


switch ($method) {
	// FEEDS
	case 'feed_add':
		$parameter->url = (isset($_REQUEST['url'])) ? $_REQUEST['url'] : null;

		$api->feed_add($parameter);
	break;
	case 'feed_get':
		$feed_id = (isset($_REQUEST['feed_id'])) ? $_REQUEST['feed_id'] : null;
		//$feed_id = (isset($_REQUEST['feed_id'])) ? $_REQUEST['feed_id'] : null;
	break;

	// ITEM
	case 'item_get':break;

	// KEYWORD
	case 'keyword_get':break;

	// STAT
	case 'stat_get_total_feeds' :
		$parameter->object = "total_feeds";
		echo $api->stat_get($parameter);
	break;
	case 'stat_get_total_items':
		$parameter->object = "total_items";
		echo $api->stat_get($parameter);
	break;
}
