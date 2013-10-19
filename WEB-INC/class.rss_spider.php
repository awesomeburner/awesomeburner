<?php
// класс индексирования
class feed_spider {
	var $cRssLib;

	// конструктор: определяем в нем необходимые классы
	function __construct() {
		$this->cDownload	= new downloader();
		$this->cData	= new data();
		$this->cFeed	= new feed();
		$this->feed		= new feed();
		$this->cKeyword	= new keyword();
	}
	
	// индексирование всех rss-каналов
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
	
	/**
	 * индексирование всех rss-каналов
	 * @return type 
	 */
	public function spider_all($intLimit = 10) {
		$intAllFeeds = $this->cData->count_feeds();
		$cio = 1;

		for ($i = 0; $i < $intAllFeeds; $i++) {
			// выбираем из БД список каналов в массив
			$arrFeeds	= $this->cData->get_feeds("", "", "`update`", "ASC", $i, $intLimit);
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

	// индексирование определенного канала
	function spider_channel($intChannelID, $strUrl) {
		// закачиваем ресурс
		$strData = $this->cDownload->get_resource($strUrl);
		
		if ($strData == false) {
			return false;
		}

		// обрабатываем документ
		$arrData = $this->cFeed->parse($strData);

		$arrFeed = $arrData['feed'];
		$arrItems = $arrData['items'];

		// если данные присутствуют, делаем следующее
		if ($arrFeed) {
			$arrFeed->feed_id = $intChannelID;
			$arrFeed->feed_url = $strUrl;
			$arrFeed->lastindex = date("Ymdhis");
			//$arrFeedData->feed->update = date("Ymdhis");

			// отправляем массив данных на сохранение
			$this->cData->save_feed($arrFeed->feed_id, $arrFeed->feed_url, $arrFeed->lastindex, $arrFeed->lastbuilddate_int, $arrFeed->pubdate_int, null, $arrFeed->title, $arrFeed->link, $arrFeed->description, $arrFeed->language, $arrFeed->copyright, $arrFeed->managingeditor, $arrFeed->webmaster, $arrFeed->pubdate, $arrFeed->lastbuilddate, $arrFeed->category, $arrFeed->generator, $arrFeed->docs, $arrFeed->cloud, $arrFeed->ttl, $arrFeed->image_url, $arrFeed->image_title, $arrFeed->image_link);
			
			for ($intCountItems = 0, $intNumItems = count($arrItems); $intCountItems < $intNumItems; $intCountItems++) {
				unset($itemsum);
				$arrItems[$intCountItems]->feed_id = $intChannelID;
				
				//
				
				$item_id = $this->cData->save_item("null", $arrItems[$intCountItems]->feed_id, $arrItems[$intCountItems]->pubdate_int, $arrItems[$intCountItems]->title, $arrItems[$intCountItems]->link, $arrItems[$intCountItems]->description, $arrItems[$intCountItems]->author, $arrItems[$intCountItems]->category, $arrItems[$intCountItems]->comments, $arrItems[$intCountItems]->enclousure, $arrItems[$intCountItems]->guid, $arrItems[$intCountItems]->pubdate, $arrItems[$intCountItems]->source, addslashes(json_encode($arrItems[$intCountItems])));
				
				// ---
				if (isset($item_id) && $item_id!==0) {
					echo "  new item: ".$item_id."\n";
					$keywords = $this->cKeyword->extract_keywords($arrItems[$intCountItems]->title." ".$arrItems[$intCountItems]->description);

					foreach ($keywords as $k) {
						if ($this->cKeyword->check($k) == false) {
							$keyword_id = $this->cKeyword->save($k);
						} else {
							$keyword_id = $this->cKeyword->get($k);
						}

						if ($item_id !== 0 || $item_id !== '' || $keyword_id !== 0 || $keyword_id !== '') {
							mysql_query("INSERT INTO `feed_keyword_item` (`keyword_id`,`item_id`) VALUES ('{$keyword_id}','{$item_id}')");
						}
					}
					unset($keywords);
				}
			}
			return true;
		}
		return false;
	}
};
