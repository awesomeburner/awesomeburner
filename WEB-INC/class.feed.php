<?php
// класс обаботки xml-файла
class agregator_feed extends clsMysql {
	public function __call($ad, $parameter = null) {
		return array("status" => array("code" => 6, "message" => "action not exists"));
	}
	
    public function add($parameter = null) {
		if (!isset($parameter['url'])) {
			return array("status" => array("code" => 4, "message" => "url not specified"));
		}
		
		// TODO: check exists url in database

		$this->Query("INSERT INTO `feed_feeds` (`feed_url`) VALUES ('{$parameter['url']}')", true);

		if (!$this->insert_id) {
			return array("status" => array("code" => 5, "message" => "url not added"));
		} 
		
		return array("result" => array("feed_id" => $this->insert_id), "status" => array("code" => 0, "message" => "ok"));
	}
	
	public function delete($parameter = null) {
		if (isset($parameter['feed_id'])) {
			$this->Query("DELETE FROM `feed_feeds` WHERE (`feed_id`={$parameter['feed_id']})");
		}

		if (isset($parameter['url'])) {
			$this->Query("DELETE FROM `feed_feeds` WHERE (`feed_url`={$parameter['url']})");
		}
		
		// TODO: delete items

		return 0;
	}

	public function get($parameter=null) {
	}
	
	public function feed_get_by_id($parameter = null) {
		if (!isset($option['where']['id'])) {
			return false;
		}
		$feedid = $option['where']['id'];
		$j['url'] = $this->Query("SELECT `feed_url` as `url` FROM `feed_feeds` WHERE `feed_id`={$feedid}");
		return $j;
	}


	public function item_get($parameter = null) {
		$feed_id = $parameter['feed_id'];
		$item_id = (isset($parameter['item_id'])) ? (int) $parameter['item_id']: 0;
		$keyword = $parameter['keyword'];
		$page = $parameter['page'];
		$limit = $parameter['limit'];

		if ($item_id == 0) {
			if ($feed_id == 0) {
				$str_query_add_feedid = null;
			} else {
				// TODO: с WHERE сделать нормально
				$str_query_add_feedid = " WHERE `feed_id`='{$feed_id}' ".(count($keyword)) ? "AND" : null;
			}
			
			if (count($keyword) == 0) {
				$str_query_add_keyword = null;
			} else {
				// TODO: при сохранении поста сделать сохранение ключевых слов
				// TODO: сделать выборку по ключевым словам
				$str_query_add_keyword = null;
				//$eferf = implode(",", $keyword);
				//$str_query_add_keyword = "`item_id` IN (SELECT item_id FROM feed_item_keyword WHERE)";
			}
			$str_query = "SELECT * FROM `feed_items` {$str_query_add_feedid} {$str_query_add_keyword}";
		} else {
			$str_query = "
			SELECT `items`.feed_id, (SELECT feed_feeds.title FROM feed_feeds WHERE feed_feeds.feed_id = `items`.feed_id) as feed_title,
			`items`.item_id,
			`items`.pubdate_int,
			`items`.title,
			`items`.checksum,
			`items`.link,
			`items`.description,
			`items`.author,
			`items`.category,
			`items`.guid,
			`items`.pubdate,
			`items`.source,
			`enclosure`.`hash_1`,
			`enclosure`.`hash_2`,
			`enclosure`.`hash_32`
			FROM `feed_items` `items`
			LEFT JOIN `feed_item_enclosure` `enclosure` ON `enclosure`.`item_id` = `items`.item_id
			WHERE `items`.`item_id`='{$item_id}'
			";
		}
		
		$r = $this->Query($str_query, false);
		$rn = $this->num_rows;
		
		return array("result" => array("total" => $rn, "items" => $r), "status" => array("code" => 0, "message" => "ok"));

		// $api->feed("get", array("feed_id" => 1, "page" => 1, "limit" => 20, "keyword" => array("one", "two")))
	}


	/**
	* Обрработка XML-документа, преобразование в массив
	*
	* Param: str $strXmlDoc
	* Return: array
	*/
	function parse($strXmlDoc) {
		$boolDocument = false;
		$arrRssData = null;

		$p = xml_parser_create();
		xml_parse_into_struct($p, $strXmlDoc, $vals, $index);
		xml_parser_free($p);

		$type = 0;
		$tmp[] = array("", "", "");
		$id = 0;

		for ($i = 0, $cvals = count($vals); $i < $cvals; ++$i) {
			if (($vals[$i]['tag'] == "RSS") && ($vals[$i]['type'] == "open")) {
				switch ($vals[$i]['attributes']['VERSION']) {
					case "0.91" : break;
					case "1.0" : break;
					case "2.0" :
						// title
						// link
						// description
						// language
						// pubDate
						break;
				}

				$boolDocument = true;
			}
		}
		
		if($boolDocument === false) {
			return false;
		}
		
		for ($i = 0, $cvals = count($vals); $i < $cvals; ++$i) {
			if (($vals[$i]['tag'] == "CHANNEL") && ($vals[$i]['type'] == "open")) {
				$id = $vals[$i]['level'] + 1;
			}

			if (($type == 0) && ($id == $vals[$i]['level'])) {
				switch ($vals[$i]['tag']) {
					case "TITLE" :
						$channel['TITLE'] = addslashes($vals[$i]['value']);
					break;
					case "LINK" :
						$channel['LINK'] = $vals[$i]['value'];
					break;
					case "DESCRIPTION" :
						$channel['DESCRIPTION'] = addslashes($vals[$i]['value']);
					break;

					// ----------
					case "LANGUAGE" :
						$channel['LANGUAGE'] = $vals[$i]['value'];
					break; 
					case "COPYRIGHT" :
						$channel['COPYRIGHT'] = $vals[$i]['value'];
					break;					
					case "MANAGINGEDITOR" :
						$channel['MANAGINGEDITOR'] = $vals[$i]['value'];
					break;
					case "WEBMASTER" :
						$channel['WEBMASTER'] = $vals[$i]['value'];
					break;
					case "PUBDATE" :
						$channel['PUBDATE'] = $vals[$i]['value'];
					break;
					case "LASTBUILDDATE" :
						$channel['LASTBUILDDATE'] = $vals[$i]['value'];
					break;
					case "CATEGORY" :
						$channel['CATEGORY'] = $vals[$i]['value'];
					break;
					case "GENERATOR" :
						$channel['GENERATOR'] = $vals[$i]['value'];
					break;
					case "DOCS" :
						$channel['DOCS'] = $vals[$i]['value'];
					break;
					case "CLOUD" :
						$channel['CLOUD'] = $vals[$i]['value'];
					break;
					case "TTL" :
						$channel['TTL'] = $vals[$i]['value'];
					break;
					
					case "IMAGE" :
						$ci = $i + 1;

						if ($vals[$ci]['tag'] == "URL" && ($vals[$ci]['level'] == ($vals[$i]['level'] + 1))) {
							$image['URL'] = $vals[$ci]['value'];
							$i++;
						}
						if ($vals[$ci]['tag'] == "LINK" && ($vals[$ci]['level'] == ($vals[$i]['level'] + 1))) {
							$image['LINK'] = $vals[$ci]['value'];
							$i++;
						}
						if ($vals[$ci]['tag'] == "TITLE" && ($vals[$ci]['level'] == ($vals[$i]['level'] + 1))) {
							$image['TITLE'] = addslashes($vals[$ci]['value']);
							$i++;
						}
					break;
				}
			} else {
				switch ($vals[$i]['tag']) {
					case "TITLE":
						$tmp["TITLE"] = addslashes($vals[$i]['value']);
					break;
					case "LINK" :
						$tmp["LINK"] = $vals[$i]['value'];
					break;
					case "DESCRIPTION" :
						$tmp['DESCRIPTION'] = addslashes($vals[$i]['value']);
						break;
					case "AUTHOR" :
						$tmp['AUTHOR'] = $vals[$i]['value'];
					break;
					case "CATEGORY" :
						$tmp['CATEGORY'] = $vals[$i]['value'];
					break;
					case "COMMENTS" :
						$tmp['COMMENTS'] = addslashes($vals[$i]['value']);
					break;
					case "ENCLOSURE" :
						//print_r($vals[$i]);
						$tmp['ENCLOSURE'] = $vals[$i]['attributes'];
					break;
					case "GUID" :
						$tmp['GUID'] = $vals[$i]['value'];
					break;
					case "PUBDATE" :
						$tmp['PUBDATE'] = $vals[$i]['value'];
					break;
					case "SOURCE" :
						$tmp['SOURCE'] = $vals[$i]['value'];
					break;
				}
			}

			if ($vals[$i]['tag'] == "ITEM") {
				if (($vals[$i]['type'] == "open") && ($type == 0)) {
					$type = 1;
				}

				if ($vals[$i]['type'] == "close") {
					//$items[] = new container_item(null, null, $tmp['PUBDATE'], $tmp["TITLE"], $tmp["LINK"], $tmp['DESCRIPTION'], $tmp['AUTHOR'], $tmp['CATEGORY'], $tmp['COMMENTS'], $tmp['ENCLOUSURE'], $tmp['GUID'], $tmp['PUBDATE'], $tmp['SOURCE']);
					$items[] = new container_item(null, null, (date("Ymdhis", strtotime($tmp['PUBDATE']))), $tmp["TITLE"], $tmp["LINK"], $tmp['DESCRIPTION'], $tmp['AUTHOR'], $tmp['CATEGORY'], $tmp['COMMENTS'], $tmp['ENCLOSURE'], $tmp['GUID'], $tmp['PUBDATE'], $tmp['SOURCE']);
					unset($tmp);
				}
			}
		}

		//$arrRssData['feed'] = new container_feed(null, null, null, (date("Ymdhis", strtotime($channel['LASTBUILDDATE']))), (date("Ymdhis", strtotime($channel['PUBDATE']))), null, $channel['TITLE'], $channel['LINK'], $channel['DESCRIPTION'],
		$arrRssData['feed'] = new container_feed(null, null, null, $channel['LASTBUILDDATE'], $channel['PUBDATE'], null, $channel['TITLE'], $channel['LINK'], $channel['DESCRIPTION'],
		$channel['LANGUAGE'], $channel['COPYRIGHT'], $channel['MANAGINGEDITOR'], $channel['WEBMASTER'],
		$channel['PUBDATE'], $channel['LASTBUILDDATE'], $channel['CATEGORY'], $channel['GENERATOR'],
		$channel['DOCS'], $channel['CLOUD'], $channel['TTL'], $image['URL'], $image['TITLE'], $image['LINK']);

		$arrRssData['items'] = $items;

		return $arrRssData;
	} // end function getrss
	
	

	
	public function updateIndexDate($intFeedID) {
		$t = date("Ymdhis");
		mysql_query("UPDATE `feed_feeds` SET `lastindex`='{$t}' WHERE (`feed_id`={$intFeedID})");
		
		echo "\n-----\n".mysql_error()."\n-----\n";
		
		if (mysql_errno()) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Выводит массив с указанным кол-вом RSS-лент,
	 * индексация которых производилась давно
	 * @param int $num
	 * @return array 
	 */
	public function getLongTimeIndex($num = 1) {
		$tmp->sql = "SELECT * FROM `feed_feeds` ORDER BY `lastindex` ASC LIMIT {$num}";
		$tmp->query = mysql_query($tmp->sql);
		$tmp->num = mysql_num_rows($tmp->query);
		$tmp->res = null;

		if ($tmp->num == 0) {
			return null;
		}
		
		while ($r = mysql_fetch_object($tmp->query)) {
			$tmp->res[] = $r;
		}
		
		return $tmp->res;
	}
};
