<?php
// load classes
include_once("../WEB-INC/class.db.php");
include_once("../WEB-INC/class.auth.php");
include_once("../WEB-INC/class.data_viewer.php");

// classes
$cDb = new db();
$cAuth = new auth();
$cDataViewer = new Viewer();

$intChannelID	= (isset($_GET['cid'])) ? $_GET['cid'] : false;
$intPage		= (isset($_GET['p'])) ? $_GET['p'] : 1;


if ($intChannelID == false) {
	header("Location: /");
	exit;
}

$intChannelItems = $cDataViewer->count_channels_items($intChannelID);
$intStep = 10;

if ($intChannelItems <= $intStep) {
	$intMaxPages = 1;
} else {
	$intMaxPages = $intChannelItems / $intStep;
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
		if ($intStop < $intChannelItems) {
			$to_next = true;
		}
	}
}

$but_to_back = ($to_back) ? "<a href='/rss_admin/channel.php?cid={$intChannelID}&p=".($intPage - 1)."'>< Back</a>&nbsp;&nbsp;" : null;
$but_to_next = ($to_next) ? "<a href='/rss_admin/channel.php?cid={$intChannelID}&p=".($intPage + 1)."'>Next ></a>" : null;


//exit;

$chi = $cDataViewer->get_channel_items($intChannelID, $intStart, $intStep);
$chin = count($chi['items']);

$return = null;
$channel_id	= $chi['channel']['channel_id'];
$url		= $chi['channel']['url'];
$link		= $chi['channel']['chiLink'];
//$link		= $chi[$c]['info']['chiLink'];
$title		= ($chi['channel']['chiTitle'] <> "") ? $chi['channel']['chiTitle'] : "unknown";
//print_r($chi);
$strReturnChannelInfo =<<<NN
<div>
	<b>$title</b>
</div>
<div>
	$link
</div>

NN;

for ($c = 0; $chin > $c; $c++) {
	$item_title = $chi['items'][$c]['title'];
	$item_link = $chi['items'][$c]['link'];
	$item_id = $chi['items'][$c]['item_id'];
	$item_descr = $chi['items'][$c]['description'];
	//$item_pubdate = $chi['items'][$c]['pubDate'];
	$item_pubdate = date("h:i:s d.m.Y", strtotime($chi['items'][$c]['pubDate']));
	$item_category = $chi['items'][$c]['category'];
	$item_comments	= (isset($chi['items'][$c]['comments'])) ? $chi['items'][$c]['comments'] : null;
	$item_comments	= ($item_comments) ? "<a href='$item_comments'>Comments</a>" : null;

	
	$num = $c + $intStart + 1;
	$return .=<<<PPH
			<tr> 
				<td>$item_pubdate.</td>
				<td><b><span><a href="/rss_admin/item.php?item_id=$item_id">$item_title</a></span></b></td>
				<td><input type=button></td>
			</tr>
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
				<LI class="first"><A href="/rss_admin/?view=last" accesskey="1" title="">Last posts</A></LI>
				<LI class="first"><A href="/rss_admin/?view=channels" accesskey="1" title="">Channels list</A></LI>
			</UL>
		</DIV>

		<DIV id="content">
			<DIV id="primaryContentContainer">
				<DIV id="primaryContent">
					<DIV class="box">
						<H3>Channel <?=$strReturnChannelInfo;?></H3>
						<DIV class="boxContent">
<?php echo "<table>".$return."</table>".$but_to_back.$but_to_next; ?>
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