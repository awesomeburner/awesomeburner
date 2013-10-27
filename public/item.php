<?php
ini_set("error_reporting", 1);
ini_set("display_errors", 1);

include "../WEB-INC/conf.php";
include "../WEB-INC/class.mysql.php";
include "../WEB-INC/class.api.php";

$intItemID	= (isset($_GET['item_id'])) ? $_GET['item_id'] : false;

if (!$intItemID) {
	header("Location: ./");
	exit();
}


$api = new clsApi();
$result = $api->feed("item_get", array("item_id" => $intItemID));

if (!$result['result']['total']) {
	header("location: /");
	exit();
}

$item = $result['result']['items'];


/*include_once("../WEB-INC/class.contain.feed.php");
include_once("../WEB-INC/class.contain.item.php");
include_once("../WEB-INC/class.data.php");
include_once("../WEB-INC/class.post.php");

//$cDb = new db();
$cData = new data();
$bbpost = new post();
*/

//$arrItem = $bbpost->get_item($intItemID);
//$arrFeed = $cData->get_feed($arrItem->feed_id);

#-------------------------------------------------------------------------------

$intFeedID    = $item->feed_id;
$strFeedTitle = ($item->feed_title) ? $item->feed_title : "unknown";
$strFeedLink  = $item->link;
$strFeedDescr = $item->description;

//$strImgUrl   = $arrFeed->image_url;
//$strImgTitle = $arrFeed->image_title;
//$strImgLink  = $arrFeed->image_link;

$strItemID        = $item->item_id;
$strItemTitle     = $item->title;
$strItemLink      = $item->link;
$strItemDescription = $item->description;
$strItemPubDate   = date(GA_A_FORMATDATE, strtotime($item->pubdate_int));
$strItemCategory  = $item->category;
$strItemComments	= ($item->comments) ? "<a href='".$item->comments."'>Comments</a>" : null;
$strItemEnclosure = (isset($item->hash_32) && isset($item->hash_2) && isset($item->hash_1)) ? "<img src='/static/{$item->hash_1}/{$item->hash_2}/{$item->hash_32}' alt='' />" : null;

#----	---------------------------------------------------------------------------

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
					<div class="enclosure">
						$strItemEnclosure
					</div>
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
			<br />
			<div class="clear"></div>
PPH;

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title><?=$strItemTitle;?> - BeeBlog.org</title>
		<link href="css/960.css" rel="stylesheet" type="text/css" />
        <link href="css/v0.1.css" rel="stylesheet" type="text/css" />
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
		<script>
            $.getJSON('api.php?method=stats&action=get', function(data) {
				$('#getnumderrssfeeds').append(data.result.feeds);
				$('#getnumderarticles').append(data.result.items);
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
