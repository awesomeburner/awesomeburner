<?php
// load classes
include_once("../WEB-INC/class.db.php");
include_once("../WEB-INC/class.auth.php");
include_once("../WEB-INC/class.data_viewer.php");

// classes
$cDb = new db();
$cAuth = new auth();
$cDataViewer = new Viewer();

$intItemID	= (isset($_GET['item_id'])) ? $_GET['item_id'] : false;

if ($intItemID == false) {
	header("Location: /");
	exit();
}


$chi = $cDataViewer->get_item($intItemID);

//print_r($chi);

$return = null;

$channel_id = $chi['channel']['chID'];
$channel_url = $chi['channel']['chURL'];
$channel_title = $chi['channel']['chiTitle'];
$channel_link = $chi['channel']['chiLink'];
$channel_descr = $chi['channel']['chiDescr'];
$channel_img_url = $chi['channel']['chimUrl'];
$channel_img_title = $chi['channel']['chimTitle'];
$channel_img_link = $chi['channel']['chimLink'];
$channel_img_width = $chi['channel']['chimWidth'];
$channel_img_height = $chi['channel']['chimHeight'];

$channel_title		= ($channel_title <> "") ? $channel_title : "unknown";


$item_title = $chi['items']['title'];
$item_link = $chi['items']['link'];
$item_id = $chi['items']['item_id'];
$item_descr = $chi['items']['description'];
//$item_pubdate = $chi['items'][$c]['pubDate'];
$item_pubdate = date("h:i:s d.m.Y", strtotime($chi['items']['pubDate']));
$item_category = $chi['items']['category'];
$item_comments	= (isset($chi['items']['comments'])) ? $chi['items']['comments'] : null;
$item_comments	= ($item_comments) ? "<a href='$item_comments'>Comments</a>" : null;

$strReturnChannelInfo =<<<NN
<div>
	<b>$channel_title</b>
</div>
<div>
	$channel_link
</div>

NN;



	
	$return .=<<<PPH
			<div class=item> 
				<div class=date>
					<b><font color="#999999">$item_pubdate</font></b>
				</div>
				<div class=title>
					<b><span><a href="$item_link">$item_title</a></span></b>
				</div>
				<div class=descr>
					$item_descr
				</div>
				<div class=comments>
					$item_comments
				</div>
				<div class=foot>
					<div class=link>
						<a href="$item_link" target="_blank">Source</a>
					</div>
				</div>
			</div>
			<br>
			<DIV class="clear"></DIV>
PPH;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">

<TITLE>GlobalArchive.ru</TITLE>
<META name="keywords" content="">
<META name="description" content="">
<LINK href="/css/rsslib.css" rel="stylesheet" type="text/css">
</HEAD><BODY>

<DIV id="outer">
	<DIV id="header">
		<H1><A href="/">GlobalArchive.ru</A></H1>
		<H2>alpha</H2>
	</DIV>

	<DIV class="content_bottom">
		<DIV id="menu">
			<UL>
				<LI class="first"><A href="/rss_admin/" accesskey="1" title="">Home</A></LI>
				<LI class="first"><A href="/rss_admin/l.php" accesskey="2" title="">Last posts</A></LI>
				<LI class="first"><A href="/rss_admin/cs.php" accesskey="3" title="">Channels list</A></LI>
			</UL>
		</DIV>

		<DIV id="content">
			<DIV id="primaryContentContainer">
				<DIV id="primaryContent">
					<DIV class="box">
						<H3>Channel <?=$strReturnChannelInfo;?></H3>
						<DIV class="boxContent">
<?php echo $return.$but_to_back.$but_to_next; ?>
						</DIV>
					</DIV>
				</DIV>
			</DIV>

			<DIV class="clear"></DIV>
		</DIV>
	</DIV>

	<DIV id="footer">
		<P>Copyright &copy; 2008-2009 GlobalArchive.ru</P>
	</DIV>
</DIV>

</BODY>
</HTML>
<?php
$cDb->close();
?>