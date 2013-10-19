<?php
// load classes
include_once("../WEB-INC/class.db.php");
include_once("../WEB-INC/class.auth.php");
include_once("../WEB-INC/class.data_viewer.php");

// global vars
$strPage		= (isset($_GET['view'])) ? $_GET['view'] : null;
$intChannelID	= (isset($_GET['channel'])) ? $_GET['channel'] : false;

$username = (isset($_POST['username'])) ? $_POST['username'] : null;
$password = (isset($_POST['password'])) ? sha1($_POST['password']) : null; //sha1 encryption has been used here this can be changed when changed, you will also need to change the passwords in the config file.

// classes
$cDb = new db();
$cAuth = new auth();
$cDataViewer = new Viewer();

//$cDataViewer->get_last_items();
$cDataViewer->get_items(null, "NOW", null, "pubDate", "ASC", 0, 10);

for ($c = 0; $cDataViewer->last_posts['count'] > $c; $c++) {
	$channel_title	= $cDataViewer->last_posts['data'][$c]['channel']['chiTitle'];
	$channel_url	= $cDataViewer->last_posts['data'][$c]['channel']['chURL'];
	$channel_link	= $cDataViewer->last_posts['data'][$c]['channel']['chiLink'];
			
	$item_id		= $cDataViewer->last_posts['data'][$c]['item']['item_id'];
	$item_title		= $cDataViewer->last_posts['data'][$c]['item']['title'];
	$item_link		= $cDataViewer->last_posts['data'][$c]['item']['link'];
	$item_descr		= $cDataViewer->last_posts['data'][$c]['item']['description'];
	$item_pubDate	= date('d.m.Y h:i', strtotime($cDataViewer->last_posts['data'][$c]['item']['pubDate']));
	$item_comments	= (isset($cDataViewer->last_posts['data'][$c]['item']['comments'])) ? $cDataViewer->last_posts['data'][$c]['item']['comments'] : null;
	$item_comments	= ($item_comments) ? "<a href='$item_comments'>Читать дальше &rarr;</a>" : null;

	$return .=<<<PPH
			<div class=item> 
				<div class=date>
					<b><font color="#999999">$item_pubDate</font></b>
				</div>
				<div class=title>
					<b><span><a href="/rss_admin/item.php?item_id=$item_id">$item_title</a></span></b>
				</div>
				<div class=foot>
					<div class=sorce>
						<font size=-1 color="#707070">Source: <B><a href="$channel_link">$channel_title</a></B></font>
					</div>
				</div>
			</div>
			<br/>
PPH;
}
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
						<H3>Last posts</H3>
						<DIV class="boxContent">
<? echo $return;
echo $auth_ret.$ergerg;
echo "123".$cDataViewer->last_posts['count'];
?>
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