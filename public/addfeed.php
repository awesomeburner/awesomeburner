<?php
ini_set('error_reporting', 1);
ini_set('display_errors', 1);

include "../WEB-INC/conf.php";
include "../WEB-INC/class.mysql.php";
include "../WEB-INC/class.api.php";

$api = new clsApi();

if (isset($_POST['sum']) == md5(date("Ymd"))) {
	$api->feed("add", array("url" => $_POST['url'], "lang" => "en"));
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Add new RSS-feed - BeeBlog.org</title>
	        <link href="css/960.css" rel="stylesheet" type="text/css" />
	        <link href="css/v0.1.css" rel="stylesheet" type="text/css" />
	        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
	</head>
	<body>
        <div class="container_12" id="header">
            <div class="grid_12">
                RSS-feeds: <span id="getnumderrssfeeds" style="font-weight: 600"></span>
                Articles <span id="getnumderarticles" style="font-weight: 600"></span>
                <a href="addfeed.php">Add new RSS-feed</a>
                <a href="stat.php">View statistics</a>
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
				<div class="item">
					<div class="hentry">
					<h2>Add new feed</h2>
					<form action="addfeed.php" method="post">
						<p>URL: <input type="text" name="url" value="" class="font190" style="width: 100%;" /></p>
						<input type="submit" value="Add" class="font190" />
						<input type="hidden" name="sum" value="<?php echo md5(date("Ymd")); ?>" />
					</form>
					</div>
				</div>
			</div>
			<br class="clear"/>
		</div>
		<script>
            $.getJSON('api.php?method=stats&action=get', function(data) {
				$('#getnumderrssfeeds').append(data.result.feeds);
				$('#getnumderarticles').append(data.result.items);
            });
		</script>
	</body>
</html>
