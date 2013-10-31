<?php
// 1. get all feeds from database
// 2. (feed get content)


// класс индексирования
class agregator_crawler extends clsMysql {
	var $cRssLib;

	// конструктор: определяем в нем необходимые классы
	//function __construct() {
	//	$this->cDownload	= new downloader();
	//	$this->cData	= new data();
	//	$this->cFeed	= new agregator_feed();
	//	$this->feed		= new agregator_feed();
	//	$this->cKeyword	= new keyword();
	//}


	/**
	 * индексирование всех rss-каналов
	 * @return type
	 */
	public function all($parameters = null) {
		$data = new data();

		if (isset($parameters['limit'])) {
			$intLimit = $parameters['limit'];
		} else {
			$intLimit = 10;
		}

		$intAllFeeds = $data->count_feeds();
		$cio = 1;

		print "Total RRS-feeds:" . $intAllFeeds . "\n";

		for ($i = 0; $i < $intAllFeeds; $i++) {
			// выбираем из БД список каналов в массив
			$arrFeeds	= $data->get_feeds("", "", "`update`", "ASC", $i, $intLimit);
			$intFeeds = count($arrFeeds);
		
			if (!$arrFeeds) {
				continue;
			}

			// перебираем весь массив
			for ($cc = 0; $intFeeds > $cc; ++$cc) {
				$intFeedId			= $arrFeeds[$cc]->feed_id;
				$strFeedUrl			= $arrFeeds[$cc]->feed_url;
				$strFeedLastIndex	= $arrFeeds[$cc]->lastindex;

				$_timer_start = date("Ymdhis");
				// качаем канал
				$_spider_channel_status = $this->spider_channel($intFeedId, $strFeedUrl);

				$_timer_stop = date("Ymdhis");
				echo "".($cio++).". {$strFeedUrl} ... time: ".($_timer_stop - $_timer_start)." ... [".(($_spider_channel_status == true) ? "OK" : "ERR")."]\n";
			}
		}
		return true;
	}



	/**
	 * индексирование всех rss-каналов
	 */
	public function indexItem($FeedId) {
		// выбираем из БД список каналов в массив
		$feed = $this->cData->get_feed($FeedId);

		$intFeedId			= $feed->feed_id;
		$strFeedUrl			= $feed->feed_url;
		$strFeedLastIndex	= $feed->lastindex;

		$_timer_start = date("Ymdhis");
		// качаем канал
		$_spider_channel_status = $this->spider_channel($intFeedId, $strFeedUrl);

		$_timer_stop = date("Ymdhis");
		//echo "".($cio++).". {$strFeedUrl} ... time: ".($_timer_stop - $_timer_start)." ... [".(($_spider_channel_status == true) ? "OK" : "ERR")."]\n";
		echo "url: {$strFeedUrl}\n";
		echo "time: ".($_timer_stop - $_timer_start)."\n";
		echo "scan status: ".(($_spider_channel_status == true) ? "OK" : "ERR")."\n";
	}

	/**
	 *
	 * @param int $feed_id
	 */
	public function spider_feed($feed_id) {
		// сканируем rss
		$this->indexItem($feed_id);
		// обновляем дату последнего сканирования,
		// независимо от результата сканирования
		$this->feed->updateIndexDate($feed_id);
	}
	
	// индексирование определенного канала
	function spider_channel($intChannelID, $strUrl) {
		$download = new downloader();
		$feed = new agregator_feed();
		$data = new data();
		$keyword = new keyword();

		// закачиваем ресурс
		$str_data = $download->get_resource($strUrl);
		
		if ($str_data == false) {
			return false;
		}

		// обрабатываем документ
		$arrData = $feed->parse($str_data);

		$arrFeed = $arrData['feed'];
		$arrItems = $arrData['items'];

		// если данные присутствуют, делаем следующее
		if ($arrFeed) {
			$arrFeed->feed_id = $intChannelID;
			$arrFeed->feed_url = $strUrl;
			$arrFeed->lastindex = date("Ymdhis");
			//$arrFeedData->feed->update = date("Ymdhis");

			// отправляем массив данных на сохранение
			$data->save_feed($arrFeed->feed_id, $arrFeed->feed_url, $arrFeed->lastindex, $arrFeed->lastbuilddate_int, $arrFeed->pubdate_int, null, $arrFeed->title, $arrFeed->link, $arrFeed->description, $arrFeed->language, $arrFeed->copyright, $arrFeed->managingeditor, $arrFeed->webmaster, $arrFeed->pubdate, $arrFeed->lastbuilddate, $arrFeed->category, $arrFeed->generator, $arrFeed->docs, $arrFeed->cloud, $arrFeed->ttl, $arrFeed->image_url, $arrFeed->image_title, $arrFeed->image_link);
			
			for ($intCountItems = 0, $intNumItems = count($arrItems); $intCountItems < $intNumItems; $intCountItems++) {
				unset($itemsum);
				$arrItems[$intCountItems]->feed_id = $intChannelID;

				//print_r($arrItems[$intCountItems]);

				$item_id = $data->save_item("null", $arrItems[$intCountItems]->feed_id, $arrItems[$intCountItems]->pubdate_int, $arrItems[$intCountItems]->title, $arrItems[$intCountItems]->link, $arrItems[$intCountItems]->description, $arrItems[$intCountItems]->author, $arrItems[$intCountItems]->category, $arrItems[$intCountItems]->comments, $arrItems[$intCountItems]->enclousure, $arrItems[$intCountItems]->guid, $arrItems[$intCountItems]->pubdate, $arrItems[$intCountItems]->source, addslashes(json_encode($arrItems[$intCountItems])));

				if (isset($item_id) && $item_id > 0) {
					echo "  new item: ".$item_id."\n";
					
					// Save enclosure
					if (isset($arrItems[$intCountItems]->enclousure['URL']) && $arrItems[$intCountItems]->enclousure['LENGTH'] > 0) {
						$enclosure_tmp = array();
						// TODO: Download file
						// ...
						
						$enclosure_tmp['hash_32'] = md5($arrItems[$intCountItems]->enclousure['URL']);
						$enclosure_tmp['hash_2'] = substr($enclosure_tmp['hash_32'], 0, 2);
						$enclosure_tmp['hash_1'] = substr($enclosure_tmp['hash_32'], 0, 1);
						$enclosure_tmp['length'] = $arrItems[$intCountItems]->enclousure['LENGTH'];
						$enclosure_tmp['type'] = addslashes($arrItems[$intCountItems]->enclousure['TYPE']);
						$enclosure_tmp['url'] = addslashes($arrItems[$intCountItems]->enclousure['URL']);
						
						$_e_p = "../public/static";
						
						// create folder in static, static/a/ab/
						if (!is_dir($_e_p."/".$enclosure_tmp['hash_1'])) {
							mkdir($_e_p."/".$enclosure_tmp['hash_1']);
						}
						if (!is_dir($_e_p."/".$enclosure_tmp['hash_1']."/".$enclosure_tmp['hash_2'])) {
							mkdir($_e_p."/".$enclosure_tmp['hash_1']."/".$enclosure_tmp['hash_2']);
						}

						// get file from server, save in static
						file_put_contents($_e_p."/".$enclosure_tmp['hash_1']."/".$enclosure_tmp['hash_2']."/".$enclosure_tmp['hash_32'], file_get_contents($enclosure_tmp['url']));
						///$_e = file_get_contents($enclosure_tmp['url']);
						
						$data->feed_item_enclosure_add($item_id, $enclosure_tmp['hash_1'], $enclosure_tmp['hash_2'], $enclosure_tmp['hash_32'], $enclosure_tmp['length'], $enclosure_tmp['type'], $enclosure_tmp['url']);
						
						unset($enclosure_tmp);
					}
					
					$arr_keywords = $keyword->extract_keywords($arrItems[$intCountItems]->title." ".$arrItems[$intCountItems]->description);

					foreach ($arr_keywords as $k) {
						if ($keyword->check($k) == false) {
							$keyword_id = $keyword->save($k);
						} else {
							$keyword_id = $keyword->get($k);
						}

						if ($item_id !== 0 || $item_id !== '' || $keyword_id !== 0 || $keyword_id !== '') {
						//	mysql_query("INSERT INTO `feed_keyword_item` (`keyword_id`,`item_id`) VALUES ('{$keyword_id}','{$item_id}')");
						}
					}
					unset($arr_keywords);
				}
			}
			return true;
		}
		return false;
	}
};
