<?php
DEFINE("DB_TABLE_PREFIX", "feed_");

class data extends clsMysql {
	var $Feed = null;
	var $Feeds = null;

	function feed_item_enclosure_add($item_id, $hash_1, $hash_2, $hash_32, $length, $type, $url) {
		$this->Query("INSERT INTO `feed_item_enclosure` (`item_id`, `hash_1`, `hash_2`, `hash_32`, `length`, `type`, `url`) VALUES ('{$item_id}', '{$hash_1}', '{$hash_2}', '{$hash_32}', '{$length}', '{$type}', '{$url}')", false);
		return true;
	}

	function save_feed($feed_id, $feed_url, $lastindex, $lastbuilddate_int, $pubdate_int, $update, $title, $link, $description, $language, $copyright, $managingeditor, $webmaster, $pubdate, $lastbuilddate, $category, $generator, $docs, $cloud, $ttl, $image_url, $image_title, $image_link) {
		// insert check changes function

		$rows = $this->Query("SELECT * FROM `feed_feeds` WHERE `feed_id`='{$feed_id}' LIMIT 1", true);

		//print_r($rows);return false;
		//$rows = mysql_fetch_object($q1);

        $arrFeed = new container_feed($rows->feed_id, $rows->feed_url, $rows->lastindex, $rows->lastbuilddate_int, $rows->pubdate_int, $rows->update, $rows->title, $rows->link, $rows->description, $rows->language, $rows->copyright, $rows->managingeditor, $rows->webmaster, $rows->pubdate, $rows->lastbuilddate, $rows->category, $rows->generator, $rows->docs, $rows->cloud, $rows->ttl, $rows->image_url, $rows->image_title, $rows->image_link);

		if (($arrFeed->feed_id == $feed_id) && ($arrFeed->feed_url == $feed_url)) {
			if ($arrFeed->lastindex !== $lastindex) {$fields .= "`lastindex`='{$lastindex}'";}
			if ($arrFeed->lastbuilddate_int !== $lastbuilddate_int) {$fields .= ", `lastbuilddate_int`='{$lastbuilddate_int}'";}
			if ($arrFeed->pubdate_int !== $pubdate_int) {$fields .= ", `pubdate_int`='{$pubdate_int}'";}
			if ($arrFeed->update !== $update) {$fields .= ", `update`='{update}'";}
			if ($arrFeed->title !== $title) {$fields .= ", `title`='{$title}'";}
			if ($arrFeed->link !== $link) {$fields .= ", `link`='{$link}'";}
			if ($arrFeed->description !== $description) {$fields .= ", `description`='{$description}'";}
			if ($arrFeed->language !== $language) {$fields .= ", `language`='{$language}'";}
			if ($arrFeed->copyright !== $copyright) {$fields .= ", `copyright`='{$copyright}'";}
			if ($arrFeed->managingeditor !== $managingeditor){$fields .= ", `managingeditor`='{$managingeditor}'";}
			if ($arrFeed->webmaster !== $webmaster){$fields .= ", `webmaster`='{$webmaster}'";}
			if ($arrFeed->pubdate !== $pubdate) {$fields .= ", `pubdate`='{$pubdate}'";}
			if ($arrFeed->lastbuilddate !== $lastbuilddate) {$fields .= ", `lastbuilddate`='{$lastbuilddate}'";}
			if ($arrFeed->category !== $category) {$fields .= ", `category`='{$category}'";}
			if ($arrFeed->generator !== $generator) {$fields .= ", `generator`='{$generator}'";}
			if ($arrFeed->docs !== $docs){$fields .= ", `docs`='{$docs}'";}
			if ($arrFeed->cloud !== $cloud){$fields .= ", `cloud`='{$cloud}'";}
			if ($arrFeed->ttl !== $ttl){$fields .= ", `ttl`='{$ttl}'";}
			if ($arrFeed->image_url !== $image_url){$fields .= ", `image_url`='{$image_url}'";}
			if ($arrFeed->image_title !== $image_title) {$fields .= ", `image_title`='{$image_title}'";}
			if ($arrFeed->image_link !== $image_link) {$fields .= ", `image_link`='{$image_link}'";}

			$strUpdate = "UPDATE `feed_feeds` SET {$fields} WHERE (`feed_id`={$feed_id})";
			mysql_query($strUpdate);

			if (mysql_error()) {
				echo "<br>".mysql_error()."<br>".$strUpdate;
			}
		}
	}
	
	public function save_item($item_id, $feed_id, $pubdate_int, $title, $link, $description, $author, $category, $comments, $enclousure, $guid, $pubdate, $source, $json) {
		if (!$item_pubdate) {
			//$item_pubdate = $datetime;
		}
		$description_hash = md5($description);
		$strQuery9 = "SELECT `title`, `link`, `description`, `pubdate`, `category` FROM `".DB_TABLE_PREFIX."items` WHERE `feed_id`={$feed_id} AND `checksum`='{$description_hash}' LIMIT 1";

		$q3 = $this->Query($strQuery9, false);
		$q3n = $this->num_rows;

		if ($q3n == 0) {
			$description = ($description) ? "'{$description}'" : "null";
			$author = ($author) ? "'{$author}'" : "null";
			$category = ($category) ? "'".addslashes($category)."'" : "null";
			$comments = ($comments) ? "'{$comments}'" : "null";
			$enclousure = ($enclousure) ? "'{$enclousure}'" : "null";
			$pubdate = ($pubdate) ? "'{$pubdate}'" : "null";
			$guid = ($guid) ? "'{$guid}'" : "null";
			$source = ($source) ? "'{$source}'" : "null";
			
			echo "  check new article: ".$description_hash."\n";
			
			$strQuery10 = "INSERT INTO `".DB_TABLE_PREFIX."items` (`item_id`, `feed_id`, `pubdate_int`, `title`, `link`, `checksum`, `description`, `author`, `category`, `comments`, `guid`, `pubdate`, `source`) VALUES ('{$item_id}', '{$feed_id}', '{$pubdate_int}', '{$title}', '{$link}', '{$description_hash}', {$description}, {$author}, {$category}, {$comments}, {$guid}, {$pubdate}, {$source})";
			//echo $strQuery10."\n";
			$this->Query($strQuery10, false);
			$insert_id  = $this->insert_id;
			//echo "\nitem:".$insert_id;
				
			$strQuery11 = "INSERT INTO `feed_item_json` (`item_id`, `content`) VALUES ({$insert_id}, '{$json}')";
			$this->Query($strQuery11);
			
			return $insert_id;
		} else {
			print "Duplicate\n";
			return 0;
		}
	}

	function update_channel_indexdate($intChannelID, $intTimestamp) {
		$q1 = "UPDATE `feed_feeds` SET `lastindex`='{$intTimestamp}' WHERE (`channel_id`={$intChannelID})";
		mysql_query($q1);

		if (mysql_error() <> "") {
			echo "<br>".mysql_error()."<br>".$q1."<br>";
			return false;
		}

		return true;
	}

##################################




	/*
	$intLimitStart = integer
	$intLimitStep = integer
	$strSoft = ASC | DESC
	*/
	function get_feeds($intLastIndexStart, $intLastIndexStop, $strSortField, $strSort = "ASC", $intLimitStart, $intLimitStep) {
		$_sql_1 = null;
		$_sql_2 = null;
		$_sql_3 = null;

		if ($intLastIndexStart AND $intLastIndexStop) {
		if ($intLastIndexStart == "NOW") {
			$_sql_1 .= "WHERE `lastindex`>='".date("Ymdhis")."'";
		} else {
			$_sql_1 .= "WHERE `lastindex`>='{$intLastIndexStart}'";
		}

		if ($intLastIndexStop !== "") {
			$_sql_1 .= " AND `lastindex`<='{$intLastIndexStop}' ";
		}
}
		if ($strSortField <> "") {
			$_sql_3 = "ORDER BY {$strSortField} {$strLastIndexSort}";
		}

		if ($intLimitStart !== "" AND $intLimitStep !== "") {
			$_sql_2 .= "LIMIT {$intLimitStart}, {$intLimitStep}";
		}

		$i = 0;
		$strQuery1 = "SELECT * FROM `".DB_TABLE_PREFIX."feeds` {$_sql_1}{$_sql_3}{$_sql_2}";
		$doQuery = mysql_query($strQuery1);

		if (mysql_error() <> "") {
			echo mysql_error()."<br>\n".$strQuery1;
			return false;
		}

		while ($res = mysql_fetch_object($doQuery)) {
			$arrFeeds[$i++] = new container_feed($res->feed_id, $res->feed_url, $res->lastindex, $res->lastbuilddate_int, $res->pubdate_int, $res->update, $res->title, $res->link, $res->description, $res->language, $res->copyright, $res->managingeditor, $res->webmaster, $res->pubdate, $res->lastbuilddate, $res->category, $res->generator, $res->docs, $res->cloud, $res->ttl, $res->image_url, $res->image_title, $res->image_link);
		}

		return $arrFeeds;
	}

	function get_feed($intFeedID) {
		$i = 0;
		$strQuery1 = "SELECT * FROM `".DB_TABLE_PREFIX."feeds` WHERE `feed_id`={$intFeedID}";
		$doQuery = mysql_query($strQuery1);

		if (mysql_error() <> "") {
			echo mysql_error()."<br>\n".$strQuery1; return false;
		}

		$res = mysql_fetch_object($doQuery);

		$arrFeed = new container_feed($res->feed_id, $res->feed_url, $res->lastindex, $res->lastbuilddate_int, $res->pubdate_int, $res->update, $res->title, $res->link, $res->description, $res->language, $res->copyright, $res->managingeditor, $res->webmaster, $res->pubdate, $res->lastbuilddate, $res->category, $res->generator, $res->docs, $res->cloud, $res->ttl, $res->image_url, $res->image_title, $res->image_link);

		return $arrFeed;
	}

	function get_feeds_long_index($intLimitStart, $intStep)
	{
		$arrResult = $this->get_feeds(null, null, "indexdate", $strSort = "ASC", $intLimitStart, $intLimitStep);
		return $arrResult;
	}

	function get_items($intFeedID = null, $intPubDateStart, $intPubDateStop, $strSortField, $strSort = "ASC", $intLimitStart, $intLimitStep)
	{
		$i = 0;
		$sql_where = null;
		$boolWhere = false;
		
		$_strAnd = null;

		if ($intFeedID) {
			$_sql_4 = " `feed_id`={$intFeedID}";
			$boolWhere = true;
      $_strAnd = " AND";
		}

		if ($intPubDateStart == "NOW") {
			$_sql_1 .= $_strAnd." `pubdate_int`<='".date("Ymdhis")."'";
			$boolWhere = true;
		} else {
			$_sql_1 .= $_strAnd." `pubdate_int`<='{$intPubDateStart}'";
			$boolWhere = true;
		}

		if ($intPubDateStop) {
			$_sql_1 .= " AND `pubdate_int`>='{$intPubDateStop}' ";
			$boolWhere = true;
		}
		
		if ($boolWhere) {
			$sql_where = " WHERE {$_sql_4}{$_sql_1}";
		}
		
		if ($strSortField) {
			$_sql_3 = "ORDER BY {$strSortField} {$strSort}";
		}

		if ($intLimitStart !== "" AND $intLimitStep !== "") {
			$_sql_2 .= "LIMIT {$intLimitStart}, {$intLimitStep}";
		}
	
		$strQuery = "SELECT * FROM `".DB_TABLE_PREFIX."items` {$sql_where} {$_sql_3} {$_sql_2}";

		$doQuery = mysql_query($strQuery);
		$countitems = mysql_numrows($doQuery);
		
		if (mysql_error() <> "") {
			echo mysql_error()."<br>".$strQuery;
		}

		while ($res = mysql_fetch_object($doQuery)) {
			$items[$i++] = new container_item($res->item_id, $res->feed_id, $res->pubdate_int, $res->title, $res->link, $res->description, $res->author, $res->category, $res->comments, $res->enclousure, $res->guid, $res->pubdate, $res->source);
		}
		
		return $items;
	}

	function get_item($intItemID)
	{
		$strQuery = "SELECT * FROM `".DB_TABLE_PREFIX."items` WHERE `item_id`={$intItemID}";
		$doQuery = mysql_query($strQuery);
		
		if (mysql_error() <> "") {
			echo mysql_error()."<br>".$strQuery;
		}

		$res = mysql_fetch_object($doQuery);

		$item = new container_item($res->item_id, $res->feed_id, $res->pubdate_int, $res->title, $res->link, $res->description, $res->author, $res->category, $res->comments, $res->enclousure, $res->guid, $res->pubdate, $res->source);
		
		return $item;
	}

	##########

	function count_feeds() {
		return $this->Query("SELECT COUNT(*) FROM `".DB_TABLE_PREFIX."feeds`");
	}
	
	function count_feed_items($intFeedID) {
		return $this->Query("SELECT COUNT(*) FROM `".DB_TABLE_PREFIX."items` WHERE `feed_id`={$intFeedID}");
	}

	###########

	/**
	* Удаляет канал из БД
	* param: int $intFeedID
	* return: bool
	**/
	function delete_feed($intFeedID) {
		$this->Query("DELETE FROM `feed_feeds` WHERE (`feed_id`={$intFeedID})");

		return true;
	}

	/**
	* Удаляет новость из БД
	* param: int $intItemID
	* return: bool
	**/
	function delete_item($intItemID) {
		$this->Query("DELETE FROM `".DB_TABLE_PREFIX."items` WHERE (`item_id`={$intItemID})");
		
		return true;
	}

	/**
	* Удаляет новости из БД
	* param: int $intFeedID
	* return: bool
	**/
	function delete_items($intFeedID) {
		$this->Query("DELETE FROM `".DB_TABLE_PREFIX."items` WHERE (`feed_id`={$intFeedID})");

		return true;
	}
	
	public function sqldebug($mysql_error) {
		if ($mysql_error <> "") {
			echo $mysql_error."<br>".$strQuery;
		}

		return null;
	}
};
