<?php
include '../WEB-INC/conf.php';
include '../WEB-INC/class.db.php';
include '../WEB-INC/class.contain.feed.php';
include '../WEB-INC/class.contain.item.php';
include '../WEB-INC/class.data.php';

$cDb = new db();
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
		<title><?=$feed_title;?> - BeeBlog.org</title>
        <link href="/css/960.css" rel="stylesheet" type="text/css" />
        <link href="/css/v0.1.css" rel="stylesheet" type="text/css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
    </head>
    <body>
        <div class="container_12" id="header">
            <div class="grid_12">
                RSS-feeds: <span id="getnumderrssfeeds" style="font-weight: 600"></span>
                Articles: <span id="getnumderarticles" style="font-weight: 600"></span>
                <a href="/addfeed.php">Add new RSS-feed</a>
            </div>
            <div class="grid_4 tc">
                <a href="/" style="text-decoration: none; color: #000"><h1>BeeBlog.org</h1></a>
            </div>
            <div class="grid_8 tc">
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
				<h2><?=$feed_title;?></h2>
				<?=$feed_link;?>
			</div>
			<div class="grid_12">
				<?=$return;?>
			</div>
			<br class="clear"/>
		</div>
		<div class="container_12">
			<div class="grid_6 tc">
				<?=$but_to_back;?>&nbsp;
			</div>
			<div class="grid_6 tc">
				&nbsp;<?=$but_to_next;?>
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
			
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-24323088-1']);
			_gaq.push(['_setDomainName', 'beeblog.org']);
			_gaq.push(['_trackPageview']);
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
	</body>
</html>
