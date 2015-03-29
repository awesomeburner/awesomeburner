<?php
include '../WEB-INC/conf.php';
include "../WEB-INC/class.mysql.php";
include '../WEB-INC/class.contain.feed.php';
include '../WEB-INC/class.contain.item.php';
include '../WEB-INC/class.data.php';

$cData = new data();

$intFeedID	= (isset($_GET['cid'])) ? $_GET['cid'] : false;
$intPage		= (isset($_GET['p'])) ? $_GET['p'] : 1;


if ($intFeedID == false) {
	header("Location: /");
	exit;
}

$intFeedItems = $cData->count_feed_items($intFeedID);
$intStep = 10;

if ($intFeedItems <= $intStep) {
	$intMaxPages = 1;
} else {
	$intMaxPages = $intFeedItems / $intStep;
}

if ($intPage > $intMaxPages) {
	//$intPage = $intMaxPages;
}

if ($intPage <= 1) {
	$intStart = 0;
	$intStop = $intStart + $intStop;

	$to_back = false;
	if ($intMaxPages > 1) {
		$to_next = true;
	}
} else {
	$intStart = ($intPage * $intStep) - ($intStep);
	$intStop = $intStart + $intStep;

	$to_next = false;
	$to_back = true;

	if ($intMaxPages > $intPage) {
		if ($intStop < $intFeedItems) {
			$to_next = true;
		}
	}
}

$but_to_back = ($to_back) ? "<a href='/feed/{$intFeedID}-".($intPage - 1).".html'>< Back</a>&nbsp;&nbsp;" : null;
$but_to_next = ($to_next) ? "<a href='/feed/{$intFeedID}-".($intPage + 1).".html'>Next ></a>" : null;

$arrFeed = $cData->get_feed($intFeedID);
$arrItems = $cData->get_items($intFeedID, "NOW", null, "`pubdate_int`", "DESC", $intStart, 20);
$intItems = count($arrItems);

$feed_id	= $arrFeed->feed_id;
$feed_url		= $arrFeed->url;
$feed_link		= $arrFeed->link;
$feed_title		= ($arrFeed->title <> "") ? $arrFeed->title : "unknown";

for ($c = 0; $intItems > $c; $c++) {
	$item_title = $arrItems[$c]->title;
	$item_link = $arrItems[$c]->link;
	$item_id = $arrItems[$c]->item_id;
//	$item_descr = $arrItems[$c]->description;
	//$item_pubdate = $chi['items'][$c]['pubDate'];
//	$item_pubdate = date("Y.m.d, h:i:s", strtotime($arrItems[$c]->pubdate_int));
    $item_pubDate = date('d.m.Y h:i', strtotime($arrLastItems[$c]->pubdate_int));
	$item_category = $arrItems[$c]->category;
	$item_comments	= ($arrItems[$c]->comments) ? "<a href='".$arrItems[$c]->comments."'>Comments</a>" : null;

	
	$num = $c + $intStart + 1;
	$return .=<<<PPH
<div>
    <hr />
	<div class="item">
		<div>
			<div class="hentry">
				<div class="published">
					<span>$item_pubDate</span> 
				</div>
				<h3 class="entry-title">
					<a href="/post/$item_id.html" class="topic">$item_title</a>
				</h3>
				<div class="hentry">$item_descr</div>
				<!-- $item_comments -->
			</div>
		</div>
	</div>
</div>
PPH;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title><?php echo $feed_title;?> - Fresh News</title>
        <link href="/css/960.css" rel="stylesheet" type="text/css" />
        <link href="/css/v0.1.css" rel="stylesheet" type="text/css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
    </head>
    <body>
        <div id="pkg_header">
            <div class="container_12" id="header">
                <div class="grid_12">
                    <ul id="pkg_header_menu">
                        <li id="pkg_header_logo"><a href="/">Fresh News</a></li>
                        <li>RSS-feeds: <span id="getnumderrssfeeds"></span></li>
                        <li>Articles <span id="getnumderarticles"></span></li>
                        <li><a href="/addfeed.php">Add RSS</a></li>
                    </ul>
                </div>
            </div>
            <hr class="clear" />
        </div>
        <div class="container_12" id="header">
            <div class="grid_12 tc">
                <script>
                var ad_bgcolor = 'white';
                var ad_link = 'blue';
                var ad_text = 'black';
                var ad_domain = 'green';
                </script>
                <script src="http://advertious.com/show.php?pid=1108"></script>
            </div>
            <hr class="clear" />
        </div>
		<div class="container_12">
			<div class="grid_12">
				<h2><?php echo $feed_title;?></h2>
				<?php echo $feed_link;?>
			</div>
			<div class="grid_12">
				<?php echo $return;?>
			</div>
			<br class="clear"/>
		</div>
		<div class="container_12">
			<div class="grid_6 tc">
				<?php echo $but_to_back;?>&nbsp;
			</div>
			<div class="grid_6 tc">
				&nbsp;<?php echo $but_to_next;?>
			</div>
			<br class="clear"/>
		</div>
		
        <div class="container_12">
            <div class="grid_12 tc">
                <script>
                var ad_bgcolor = 'white';
                var ad_link = 'blue';
                var ad_text = 'black';
                var ad_domain = 'green';
                </script>
                <script src="http://advertious.com/show.php?pid=1105"></script>
            </div>
            <br class="clear"/>
        </div>
		
		<script type="text/javascript">
			$.getJSON('/api.php?object=stat&action=getnumderrssfeeds', function(data) {
				$.each(data, function(key, val) { $('#getnumderrssfeeds').append(val); });
			});
			$.getJSON('/api.php?object=stat&action=getnumderarticles', function(data) {
				$.each(data, function(key, val) { $('#getnumderarticles').append(val); });
			});
		</script>
		<script src="/js/google.analytics.js"></script>
	</body>
</html>
