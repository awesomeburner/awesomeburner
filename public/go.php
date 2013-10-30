<?php
/**
 * @author: vanzhiganov <i@anzhiganov.com>
 * @description: подсчет перехода по ссылке и редирект
 */

// TODO: сделать редирект по item_id
// $item_id = (isset($_GET['item_id'])) ? trim($_GET['item_id']) : 0;
$url = (isset($_GET['url'])) ? trim($_GET['url']) : null;

// TODO: check valid url

if (!$url) {
	header("location: ./");
	exit();
}

// TODO: посчитать переход

header("location: {$url}");
exit();
