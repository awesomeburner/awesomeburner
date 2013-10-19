<?php
ini_set('error_reporting', 1);
ini_set('display_errors', 1);
set_time_limit(0);

if (isset ($_GET['debug'])== 1) {
	define("DEBUG_MODE", true);
} else {
	define("DEBUG_MODE", false);
}

include '../WEB-INC/conf.php';
include '../WEB-INC/class.db.php';
include '../WEB-INC/class.contain.feed.php';
include '../WEB-INC/class.contain.item.php';
include '../WEB-INC/class.downloader.php';
include '../WEB-INC/class.feed.php';
include '../WEB-INC/class.data.php';
include '../WEB-INC/class.keyword.php';
include '../WEB-INC/class.rss_spider.php';

$cDb = new db();
$cFeedSpider = new feed_spider();

$index = (isset($_GET['index'])) ? $_GET['index'] : 1;

if ($index == 1) {
    $rss = $cFeedSpider->cFeed->getLongTimeIndex($index);
    $rss_num = count($rss);

    if ($rss_num == 0) {
	    echo "NO FEEDS";
	    exit();
    }

    for ($i = 0; $rss_num > $i; ++$i) {
		$cFeedSpider->spider_feed($rss[$i]->feed_id);
    }
} elseif ($index == 0) {
    $cFeedSpider->spider_all();
}
