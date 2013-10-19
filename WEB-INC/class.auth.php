<?php
class auth
{
    // Array where user details are stored
	var $user        = array();
    // How Long to Keep the Cookie, 0 Means Keep for Session,
    // time() + 60 * 60 * 24 * 30 for 30 days etc.	
    var $cookie_time = 0;
    // turn debug mode on and off, all but critical errors suppressed when debug
    // is turned off.
	var $debug       = true;
    // Array where user logon status
	var $logon       = null;

	var $username    = '';
	var $password    = '';
	var $level       = '';
	
    function auth()
    {
		global $_SERVER, $_GET, $_POST, $_COOKIE;

		if ($this->authenticate($_COOKIE['username'], $_COOKIE['password'])) {
			$this->logon = true;
		} else {
			$this->logon = false;
		}

		return $this->logon;
	}

	function validate ($username, $password)
	{
		// Check the Username is valid and if it is check the password is valid.
		$strSql = "SELECT * FROM `users` WHERE `email`='{$username}' AND `pass`='{$password}'";
		$que = mysql_query($strSql);

        if (mysql_error() <> "" && $this->debug == true) {
            echo mysql_error()."<br>".$strQuery;
		}

		$num = mysql_numrows($que);

		if ($num == 1) {
			$arrRes = mysql_fetch_array($que);
			$this->user = $arrRes;

			return $this->user;
		}

		return false;
	}
	
	function login($username, $password)
	{
		if ((!empty($username)) && (!empty($password))) {
			$this->username = $username;
			$this->password = $password;

			$valid = $this->validate($username, $password);
		}
		
		if ($valid == true) {
			//create user name and password cookies
			setcookie('username', $username, $this->cookie_time);
			setcookie('password', $password, $this->cookie_time);

			return true;
		}

		return false;
	}
	
	function logout($strRedirectTo = null)
	{
		//delete username and password cookies
		setcookie('username', '', time() - 3600);
		setcookie('password', '', time() - 3600);

		if ($strRedirectTo) {
			header ("Location: {$strRedirectTo}");
		}
	}
	
	function authenticate($username, $password, $level = 1)
	{
		//authenticate that the user is valid and that the users level 
		//is greater than or equal to the required level
		if ($this->validate($username, $password)) {
			if ($this->user['level'] >= $level) {
				return true;
			}
		}
		return false;
	}

	function error($msg, $type = 0)
	{
		//error handling
		switch ($type) {
			case 0 : $error_type = 'Critical'; break;
			case 1 : $error_type = 'Serious'; break;
			case 2 : $error_type = 'Minor'; break;
		}

		if ($this->debug || $type == 0) {
			return 'A '.$error_type.' error has occurred; '.$msg.'.';

			if ($type == 0) {
				exit();
			}
		}
	}
}
?>
