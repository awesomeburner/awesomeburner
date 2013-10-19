<?php
include("../WEB-INC/conf.php");
include_once("../WEB-INC/class.db.php");
include_once("../WEB-INC/class.contain.feed.php");
include_once("../WEB-INC/class.contain.item.php");
include_once("../WEB-INC/class.data.php");
include_once("../WEB-INC/class.post.php");

$cDb = new db();
$cData = new data();
$bbpost = new post();

$intItemID	= (isset($_GET['item_id'])) ? $_GET['item_id'] : false;

if (!$intItemID) {
	header("Location: /");
	exit();
}

$arrItem = $bbpost->get_item($intItemID);
$arrFeed = $cData->get_feed($arrItem->feed_id);

#-------------------------------------------------------------------------------

$intFeedID    = $arrFeed->feed_id;
$strFeedUrl   = $arrFeed->url;
$strFeedTitle = ($arrFeed->title) ? $arrFeed->title : "unknown";
$strFeedLink  = $arrFeed->link;
$strFeedDescr = $arrFeed->description;

$strImgUrl   = $arrFeed->image_url;
$strImgTitle = $arrFeed->image_title;
$strImgLink  = $arrFeed->image_link;

$strItemID        = $arrItem->item_id;
$strItemTitle     = $arrItem->title;
$strItemLink      = $arrItem->link;
$strItemDescription = $arrItem->description;
$strItemPubDate   = date(GA_A_FORMATDATE, strtotime($arrItem->pubdate_int));
$strItemCategory  = $arrItem->category;
$strItemComments	= ($arrItem->comments) ? "<a href='".$arrItem->comments."'>Comments</a>" : null;

#-------------------------------------------------------------------------------

$strReturnChannelInfo =<<<NN
<div>
	<b>$strFeedTitle</b>
</div>
<div>
$strFeedLink
</div>

NN;

$return .=<<<PPH
			<div class=item> 
				<div class=date>
					<b><font color="#999999">$strItemPubDate</font></b>
				</div>
				<div class=title>
					<b><span><a href="$strItemLink">$strItemTitle</a></span></b>
				</div>
				<div class=descr>
					$strItemDescription
				</div>
				<div class=comments>
					$strItemComments
				</div>
				<div class=foot>
					<div class=link>
						<a href="$strItemLink" target="_blank">Source</a>
					</div>
				</div>
			</div>
			<br>
			<DIV class="clear"></DIV>
PPH;

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title><?=$strItemTitle;?> - BeeBlog.org</title>
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
				<h2><?=$strItemTitle;?></h2>
			</div>
			<div class="grid_12">
				<?=$return;?>
				<!-- AddThis Button BEGIN -->
				<div class="addthis_toolbox addthis_default_style ">
				<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
				<a class="addthis_button_tweet"></a>
				<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
				<a class="addthis_counter addthis_pill_style"></a>
				</div>
				<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4f116cbd6d9ad176"></script>
				<!-- AddThis Button END -->
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
