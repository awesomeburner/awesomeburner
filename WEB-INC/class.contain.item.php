<?php
class container_item {
	var $item_id	= null;
	var $feed_id	= null;
	var $pubdate_int= null;

	var $title		= null; // tag <title>News feed online</title>
	var $link		= null; // tag <link>http://www.ru/fss.xml</link>
	var $description= null; // tag <description>Text description</description>
	var $author		= null; // tag <author>Stive V. Jobs</author>
	var $category	= null; // tag
	var $comments	= null;
	var $enclousure	= null;
	var $guid		= null;
	var $pubdate	= null;
	var $source		= null;
	
	function container_item($item_id, $feed_id, $pubdate_int, $title, $link, $description, $author, $category, $comments, $enclousure, $guid, $pubdate, $source) {
		$this->item_id		= $item_id;
		$this->feed_id		= $feed_id;
		$this->pubdate_int	= $pubdate_int;

		$this->title		= $title;
		$this->link			= $link;
		$this->description	= $description;
		$this->author		= $author;
		$this->category		= $category;
		$this->comments		= $comments;
		$this->enclousure	= $enclousure;
		$this->guid			= $guid;
		$this->pubdate		= $pubdate;
		$this->source		= $source;
	}

	function short_description_word($text, $counttext = 25, $sep = ' ') {
		$words = split($sep, $text);

		if (count($words) > $counttext) {
			$text = join($sep, array_slice($words, 0, $counttext));
		}

		return $text;
	}

	function remove_tags($strText) {
		return strip_tags($strText);
	}
};
