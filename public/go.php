<?php
/**
 * @author: vanzhiganov <i@anzhiganov.com>
 * @description: подсчет перехода по ссылке и редирект
 */

$item_id = (isset($_GET['item_id'])) ? trim($_GET['item_id']) : 0;

if ($item_id == 0) {
	header("location: ./");
	exit();
}

// TODO: посчитать переход
?>
