<?php
include '../WEB-INC/conf.php';
include '../WEB-INC/class.db.php';
//include '../WEB-INC/class.auth.php';
include '../WEB-INC/class.contain.feed.php';
include '../WEB-INC/class.contain.item.php';
include '../WEB-INC/class.data.php';

$cDb = new db();
//$cAuth = new auth();
$cData = new data();

$intFeeds = $cData->count_feeds();

$step = 10;
$page = (isset($_GET['p'])) ? $_GET['p'] : 1;
$maxpages = $intFeeds / $step;

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
		if ($stop < $intFeeds) {
			$to_next = true;
		}
	}
}

$but_to_back = ($to_back) ? "<a href='/rss/cs.php?p=".($page - 1)."'>< Back</a>&nbsp;&nbsp;" : null;
$but_to_next = ($to_next) ? "<a href='/rss/cs.php?p=".($page + 1)."'>Next ></a>" : null;

$chi = $cData->get_feeds(null, null, "`update`", "ASC", $start, 50);
$chin = count($chi);

$return = null;

for ($c = 0; $chin > $c; $c++) {
	$channel_id	= $chi[$c]->feed_id;
	$url		= $chi[$c]->feed_url;
	$link		= $chi[$c]->link;
	$title		= ($chi[$c]->title <> "") ? $chi[$c]->title : "unknown";
	$num = $c + $start + 1;
	$return .=<<<PPH
			<div class=item> 
				<div class=title>
					$num. <b><span><a href="/rss/c.php?cid=$channel_id">$title</a></span></b>
				</div>
			</div>
PPH;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>GlobalArchive.ru</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<link href="<?=linkFeedDocument("/css/rsslib.css");?>" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="outer">
			<div id="header">
				<h1><a href="/">Beeblog.Org</a></h1>
				<h2>RSS</h2>
			</div>
			<div class="content_bottom">
				<div id="menu">
					<ul>
						<li class="first"><a href="/rss/" accesskey="1" title="">Home</a></li>
						<li class="first"><a href="/rss/l.php" accesskey="2" title="">Last posts</a></li>
						<li class="first"><a href="/rss/cs.php" accesskey="3" title="">Channels list</A></li>
					</ul>
				</div>
				<div id="content">
					<div id="primaryContentContainer">
						<div id="primaryContent">
							<div class="box">
								<h3>Channels list (<?=$intChannels;?>)</h3>
								<div class="boxContent">
									<?=$return;?>
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
				<p>Copyright &copy; 2008-2009 BeeBlog.Org</p>
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
