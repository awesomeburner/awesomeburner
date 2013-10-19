<?php
ini_set('error_reporting', 1);
ini_set('display_errors', 1);

include '../WEB-INC/conf.php';
include '../WEB-INC/class.db.php';
//include(GA_C_PATH."/WEB-INC/class.auth.php");
include '../WEB-INC/class.contain.feed.php';
include '../WEB-INC/class.contain.item.php';
include '../WEB-INC/class.data.php';

$cDb = new db();
//$cAuth = new auth();
$cData = new data();

$step = 10;
$page = (isset($_GET['p'])) ? $_GET['p'] : 1;

if ($page <= 1) {
    $start = 0;
    $stop = $start + $step;

	$to_back = false;

        if ($maxpages > 1) {
		$to_next = true;
	}
} else {
	$start = $page * $step;
	$stop = $start + $step;

	$to_next = false;
	$to_back = true;
}

$arrLastItems = $cData->get_items(null, "NOW", null, "pubdate_int", "DESC", $start, 50);
$intLastItems = count($arrLastItems);

$maxpages = $intLastItems / $step;

if ($page > $maxpages) {
	$page = $maxpages;
}


if ($page <= 1) {
	$start = 0;
	$stop = $start + $step;

	$to_back = false;
	if ($maxpages > 1) {
		$to_next = true;
	}
} else {
	$start = $page * $step;
	$stop = $start + $step;

	$to_next = false;
	$to_back = true;

	if ($maxpages > $page) {
		if ($stop < $intLastItems) {
			$to_next = true;
		}
	}
}

$but_to_back = ($to_back) ? "<a href='".linkFeedAll($page - 1)."'>< Back</a>&nbsp;&nbsp;" : null;
$but_to_next = ($to_next) ? "<a href='".linkFeedAll($page + 1)."'>Next ></a>" : null;

// global vars
$strPage		= (isset($_GET['view'])) ? $_GET['view'] : null;
$intChannelID	= (isset($_GET['channel'])) ? $_GET['channel'] : false;

$username = (isset($_POST['username'])) ? $_POST['username'] : null;
$password = (isset($_POST['password'])) ? sha1($_POST['password']) : null; //sha1 encryption has been used here this can be changed when changed, you will also need to change the passwords in the config file.

for ($c = 0; $intLastItems > $c; $c++) {
  $arrFeed = $cData->get_feed($arrLastItems[$c]->feed_id);
  $strFeedTitle = (!$arrFeed->title) ? "Unknown" : $arrFeed->title;

  $item_id     = $arrLastItems[$c]->item_id;
  $item_title  = (!$arrLastItems[$c]->title) ? "Unknown" : $arrLastItems[$c]->title;
  $item_link   = $arrLastItems[$c]->link;
  $item_descr  = $arrLastItems[$c]->description;
  $item_short_descr	= $arrLastItems[$c]->short_description_word($arrLastItems[$c]->remove_tags($arrLastItems[$c]->description));
  $item_pubDate	= date('d.m.Y h:i', strtotime($arrLastItems[$c]->pubdate_int));
  $item_comments	= ($arrLastItems[$c]->comments) ? "<div class=\"comments\"><a title=\"читать комментарии\" href=\"".$arrLastItems[$c]->comments."\"><span class=\"all\">читать комментарии &rarr;</span></a></div>" : null;

  $strItemLinkToFeed = linkFeedFeed($arrFeed->feed_id);
  $strItemLinkToItem = linkFeedItem($item_id);

  $return .=<<<DDD
<div class="hentry">
	<div class="published">
		<span>$item_pubDate</span> 
	</div>
	<h2 class="entry-title"> 
	    <a href="$strItemLinkToFeed" class="blog">$strFeedTitle</a>&nbsp;&rarr;&nbsp;<a href="{$strItemLinkToItem}" class="topic">$item_title</a> 
	</h2>
	
	<div class="content">$item_short_descr&nbsp;...</div> 
	$item_comments
</div> 
<br/>
DDD;
}

//$cDb->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>BeeBlog.org</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<link href="<?=linkFeedDocument("/css/rsslib.css");?>" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="outer">
			<div id="header">
				<h1><a href="/">BeeBlog.Org</a></h1>
				<h2>beta</h2>
			</div>
			<div class="content_bottom">
				<div id="menu">
					<ul>
						<li class="first"><a href="/rss/" accesskey="1" title="">Home</a></li>
						<li class="first"><a href="/rss/l.php" accesskey="2" title="">Last posts</a></li>
						<li class="first"><a href="/rss/cs.php" accesskey="3" title="">Channels list</a></li>
						<li class="first"><a href="/rss/search.php" accesskey="3" title="">Search</a></li>
					</ul>
				</div>
				<div id="content">
					<div id="primaryContentContainer">
						<div id="primaryContent">
							<div class="box">
								<h3>Last posts</h3>
								<div class="boxContent">
									<?=$return;?>
									<?=$auth_ret;?>
									<?=$ergerg;?>
									<?=$but_to_back;?>
									<?=$but_to_next;?>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div id="footer">
				<p>Copyright &copy; 2008-2011 BeeBlog.Org</p>
			</div>
		</div>
		<script type="text/javascript">
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
