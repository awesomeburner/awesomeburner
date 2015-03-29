<?php
ini_set('error_reporting', 1);
ini_set('display_errors', 1);

include '../WEB-INC/conf.php';
include '../WEB-INC/class.mysql.php';
include '../WEB-INC/class.contain.feed.php';
include '../WEB-INC/class.contain.item.php';
include '../WEB-INC/class.data.php';

$cDb = new clsMysql();
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
$strPage        = (isset($_GET['view'])) ? $_GET['view'] : null;
$intChannelID   = (isset($_GET['channel'])) ? $_GET['channel'] : false;

$username = (isset($_POST['username'])) ? $_POST['username'] : null;
$password = (isset($_POST['password'])) ? sha1($_POST['password']) : null; //sha1 encryption has been used here this can be changed when changed, you will also need to change the passwords in the config file.

for ($c = 0; $intLastItems > $c; $c++) {
  $arrFeed = $cData->get_feed($arrLastItems[$c]->feed_id);
  $strFeedTitle = (!$arrFeed->title) ? "Unknown" : $arrFeed->title;

  $item_id     = $arrLastItems[$c]->item_id;
  $item_title  = (!$arrLastItems[$c]->title) ? "Unknown" : $arrLastItems[$c]->title;
  $item_link   = $arrLastItems[$c]->link;
//  $item_descr  = $arrLastItems[$c]->description;
//  $item_short_descr   = $arrLastItems[$c]->short_description_word($arrLastItems[$c]->remove_tags($arrLastItems[$c]->description));
  $item_pubDate = date('d.m.Y h:i', strtotime($arrLastItems[$c]->pubdate_int));
//  $item_comments  = ($arrLastItems[$c]->comments) ? "<div class=\"comments\"><a title=\"читать комментарии\" href=\"".$arrLastItems[$c]->comments."\"><span class=\"all\">читать комментарии &rarr;</span></a></div>" : null;

  $strItemLinkToFeed = linkFeedFeed($arrFeed->feed_id);
  $strItemLinkToItem = linkFeedItem($item_id);

  $return .=<<<DDD
        <div>
            <div class="item">
                <div>
                    <div class="hentry">
                        <div class="article_title">
                            <span class="published">$item_pubDate</span>&nbsp;&mdash;&nbsp;<a href="$strItemLinkToFeed" class="blog">$strFeedTitle</a>&nbsp;&mdash;&nbsp;<a href="{$strItemLinkToItem}" class="topic">$item_title</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
DDD;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Fresh News</title>
        <link href="/css/960.css" rel="stylesheet" type="text/css" />
        <link href="/css/v0.2.css" rel="stylesheet" type="text/css" />
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
                
                <?php echo $return; ?>
                
            </div>
            <br class="clear"/>
        </div>
        <div class="container_12">
            <div class="grid_6 tc">
                <?=$but_to_back;?>&nbsp;
            </div>
            <div class="grid_6 tc">
                &nbsp;<?php echo $but_to_next; ?>
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
        <script>
            $.getJSON('api.php?method=stats&action=get', function(data) {
				$('#getnumderrssfeeds').append(data.result.feeds);
				$('#getnumderarticles').append(data.result.items);
            });
        </script>
        <script src="/js/google.analytics.js"></script>
    </body>
</html>
