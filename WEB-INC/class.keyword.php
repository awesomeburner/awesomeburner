<?php
class keyword extends clsMysql {
	public function check($str_keyword) {
		$s = $this->Query("SELECT COUNT(*) FROM `feed_keywords` WHERE `keyword`='{$str_keyword}'"));

		if ($s == 0) {
			return false;
		} else {
			return true;
		}
	}

	public function get($strKeyword) {
		$s = mysql_fetch_array(mysql_query("SELECT `keyword_id` FROM `feed_keywords` WHERE `keyword`='{$strKeyword}'"));

		return $s[0];
	}

	function save($strKeyword) {
		$strQuery1 = "INSERT INTO `feed_keywords` (`keyword_id`,`keyword`) VALUES (NULL,'{$strKeyword}')";
		mysql_query($strQuery1);

		if (mysql_error() <> "") {
			//echo "<br>".mysql_error()."<br>".$strQuery1."<br>";
			return false;
		}

		return mysql_insert_id();
	}
	
	/*
	* Description
	* Param: str $strString
	*
	* return: array
	*/
	function extract_keywords($strString) {
		$strString = strip_tags(stripslashes($strString));
		$arrEx = array("~", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "+", "|", "\\", "/", "=", "-", "{", "}", "[", "]", "\"", ";", ":", "?", ">", ",", ".", "<", "«","»");

		for ($i = 0, $c = count($arrEx); $i < $c; $i++) {
			$strString = str_replace($arrEx[$i], " ", $strString);
		}

		$arr = preg_split ("/\s+/", $strString);
		$num = count($arr);

		for ($c = 0; $num > $c; $c++) {
			if ($arr[$c] == "") {
				unset($arr[$c]);
			}
		}

		return $arr;
	}

	public function get_all() {
	}
}
