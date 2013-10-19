<?php
// load classes
include_once("../WEB-INC/class.db.php");
include_once("../WEB-INC/class.auth.php");
include_once("../WEB-INC/class.data_viewer.php");

// classes
$cDb = new db();
$cAuth = new auth();
$cDataViewer = new Viewer();

$intChannels = $cDataViewer->count_channels();

$step = 10;
$page = (isset($_GET['p'])) ? $_GET['p'] : 1;
$maxpages = $intChannels / $step;
$delete = (isset($_POST['deletefeed'])) ? $_POST['deletefeed'] : null;

if ($delete !== null) {
	$cDelete = new Delete($delete, null);
}


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
		if ($stop < $intChannels) {
			$to_next = true;
		}
	}
}

$but_to_back = ($to_back) ? "<a href='/rss_admin/cs.php?p=".($page - 1)."'>< Back</a>&nbsp;&nbsp;" : null;
$but_to_next = ($to_next) ? "<a href='/rss_admin/cs.php?p=".($page + 1)."'>Next ></a>" : null;

$chi = $cDataViewer->get_channels_list($start, $step);
$chin = count($chi);

$return = null;

for ($c = 0; $chin > $c; $c++) {
	$channel_id	= $chi[$c]['channel']['channel_id'];
	$url		= $chi[$c]['channel']['url'];
	$link		= $chi[$c]['info']['link'];
	$title		= ($chi[$c]['info']['title'] <> "") ? $chi[$c]['info']['title'] : "unknown";
	$num = $c + $start + 1;
	$return .=<<<PPH
			<tr> 
				<td>$num.</td>
				<td><b><span><a href="/rss_admin/item.php?item_id=$channel_id">$title</a></span></b></td>
				<td><button type=submit name=deletefeed value=$channel_id>Delete</button></td>
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
				<LI class="first"><A href="/rss_admin/l.php" accesskey="2" title="">Last posts</A></LI>
				<LI class="first"><A href="/rss_admin/cs.php" accesskey="3" title="">Channels list</A></LI>
			</UL>
		</DIV>

		<DIV id="content">
			<DIV id="primaryContentContainer">
				<DIV id="primaryContent">
					<DIV class="box">
						<H3>Channels list (<?=$intChannels;?>)</H3>
						<DIV class="boxContent">
						<form action="cs.php" method=post>
<?
echo "<table>".$return."</table>".$but_to_back.$but_to_next;
?>
</form>
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