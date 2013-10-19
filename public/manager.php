<?php
ini_set('error_reporting', 1);
ini_set('display_errors', 1);

include '../WEB-INC/conf.php';
include '../WEB-INC/class.db.php';
include '../WEB-INC/class.contain.feed.php';
include '../WEB-INC/class.contain.item.php';
include '../WEB-INC/class.data.php';

if ($_SERVER['HTTP_HOST'] !== 'beeblog.org') {
	header('location: /');
	exit();
}

$cDb = new db();
$cData = new data();


?>

<html>
	<title>BeeBlog.org</title>
	<head>
		<link href="css/960.css" rel="stylesheet" type="text/css" />
		<link href="css/ng.css" rel="stylesheet" type="text/css" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
	</head>
	<body>
		<div class="container_12" !id="containertop">
			<div class='grid_2'>
				<div style='margin:10px;height:100px'>
					<img src='qwef' style='border:solid 2px #cc0000;' width="100px" height="100px" alt='BeeBlog.org'/>
				</div>
			</div>
			<div class="grid_10">
				<div id='profile' style='margin:10px;height:100px'>
					<h1 style='margin-left:120px'>Parked domains</h1>
					<h4 style='margin-left:120px'>Tutorial Link <a href='http://9lessons.info'>Click Here</a> - Follow me <a href='http://9lessons.info'>@9lessons</a> - Add Me <a href='http://fb.com/srinivas.tamada'>fb.com/srinivas.tamada</a> </h4>
				</div>
			</div>
			<hr class="clear" />
		</div>

		<div class="container_12">
			<div class="grid_7">
				<div class="item">
					<div class="hentry">
						<h2>Login</h2>
						<form>
							<p>Login: <input type="text"/></p>
							<p>Password: <input type="text"/></p>
							<input type="button" value="Login" />
						</form>
					</div>
				</div>
			</div>
			<div class="grid_5">
				<div class="item">
					<div class="hentry">
					<h2>Login</h2>
					<form>
						<p>Login: <input type="text"/></p>
						<p>Password: <input type="text"/></p>
						<input type="button" value="Login" />
					</form>
					</div>
				</div>
			</div>
			<br class="clear"/>
		</div>
	</body>
</html>
