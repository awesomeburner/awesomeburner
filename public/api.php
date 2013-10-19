<?php
ini_set('error_reporting', 1);
ini_set('display_errors', 1);

include 'WEB-INC/conf.php';
include 'WEB-INC/class.mysql.php';
include 'WEB-INC/class.api.php';

$object = (isset($_GET['object'])) ? strtolower(trim($_GET['object'])) : null;
$action = (isset($_GET['action'])) ? strtolower(trim($_GET['action'])) : null;
$option = (isset($_GET['option'])) ? strtolower(trim($_GET['option'])) : null;

//------

$api =& new clsApi();


switch ($object) {
	case 'stat' :
		switch ($action) {
			case 'getnumderrssfeeds' : echo $api->call($object, $action, null); break;
			case 'getnumderarticles' : echo $api->call($object, $action, null); break;
		}
		break;
}
//print_r($api->call('keyword', 'getAll', ''));

// OK
//echo $api->call('feed', 'getFeedUrlByID', array('where' => array('id' => 46)));

// getNumberRssFeeds
//echo $api->call('stat', 'getNumderRssFeeds', null);