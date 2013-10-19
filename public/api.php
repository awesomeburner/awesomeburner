<?php
#ini_set('error_reporting', 1);
#ini_set('display_errors', 1);

include '../WEB-INC/conf.php';
include '../WEB-INC/class.mysql.php';
include '../WEB-INC/class.api.php';

$method = (isset($_GET['method'])) ? strtolower(trim($_GET['method'])) : null;
$action = (isset($_GET['action'])) ? strtolower(trim($_GET['action'])) : null;
$option = (isset($_GET['option'])) ? $_GET['option'] : null;

$api = new clsApi();

print_r( $api->$method($action, $option));
exit();
switch ($object) {
	case 'stat' :
		switch ($action) {
			case 'getnumderrssfeeds' : echo $api->call($object, $action, null); break;
			case 'getnumderarticles' : echo $api->call($object, $action, null); break;
		}
		break;
	case 'feed' :
		switch ($action) {
			case 'add' :
				// /api.php?object=feed&action=add&option[url]=http://....&option[lang]=en
#				if (!isset($option['url'])) {
#					$e = array("code" => 1, "meccage": "url not specified");
#					echo json_encode($e);
#					exit();
#				}
				// TODO: валидация адреса

#				echo $api->feed("add", array("url" => $option['url'], "lang" => $option['lang']);
				break;
			default:
				break;
		}
		break;
}
//print_r($api->call('keyword', 'getAll', ''));

// OK
//echo $api->call('feed', 'getFeedUrlByID', array('where' => array('id' => 46)));

// getNumberRssFeeds
//echo $api->call('stat', 'getNumderRssFeeds', null);
