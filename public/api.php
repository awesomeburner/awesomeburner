<?php
ini_set("error_reporting", 1);
ini_set("display_errors", 1);

include "../WEB-INC/conf.php";
include "../WEB-INC/class.mysql.php";
include "../WEB-INC/class.api.php";

$method = (isset($_GET['method'])) ? strtolower(trim($_GET['method'])) : null;
$action = (isset($_GET['action'])) ? strtolower(trim($_GET['action'])) : null;
$option = (isset($_GET['option'])) ? $_GET['option'] : null;

$api = new clsApi();

if (!$method || !$action) {
	exit();
}

echo json_encode($api->$method($action, $option));

// Examples:
// FEED
// $api->feed("add", array("url" => "http://www.ru/rss", "lang" => "en"))
// $api->feed("delete", array("url" => "http://www.ru/rss"))

// $api->feed("get", array("keyword" => array("one", "two")))
// $api->feed("get", array("feed_id" => 1))
// $api->feed("get", array("feed_id" => 1))

// $api->stats("get", null)
