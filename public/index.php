<?php
ini_set('error_reporting', 1);
ini_set('display_errors', 1);

include '../WEB-INC/conf.php';
include '../WEB-INC/class.db.php';
include '../WEB-INC/class.contain.feed.php';
include '../WEB-INC/class.contain.item.php';
include '../WEB-INC/class.data.php';

$cDb = new db();
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
                       <hr />
                        <div class="published">
                            <span>$item_pubDate</span> 
                        </div>
                        <h2 class="article_title">
                            <a href="$strItemLinkToFeed" class="blog">$strFeedTitle</a>&nbsp;&rarr;&nbsp;<a href="{$strItemLinkToItem}" class="topic">$item_title</a>
                        </h2>
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
        <title>BeeBlog.org</title>
        <link href="css/960.css" rel="stylesheet" type="text/css" />
        <link href="css/v0.1.css" rel="stylesheet" type="text/css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
    </head>
    <body>
        <div class="container_12" id="header">
            <div class="grid_12">
                RSS-feeds: <span id="getnumderrssfeeds" style="font-weight: 600"></span>
                Articles <span id="getnumderarticles" style="font-weight: 600"></span>
                <a href="/addfeed.php">Add new RSS-feed</a>
                <a href="/stat.php">View statistics</a>
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
        
        <script>
            $.getJSON('api.php?object=stat&action=getnumderrssfeeds', function(data) {
                $.each(data, function(key, val) { $('#getnumderrssfeeds').append(val); });
            });
            $.getJSON('api.php?object=stat&action=getnumderarticles', function(data) {
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
