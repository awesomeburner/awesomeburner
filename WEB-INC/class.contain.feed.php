<?php
class container_feed {
	// programm
	var $feed_id            = null;
	var $feed_url           = null;
	var $lastindex          = null;
	var $lastbuilddate_int  = null;
	var $pubdate_int        = null;
	var $update             = null;

	// binding
	var $title          = null; // title
	var $link           = null; // link
	var $description    = null; // description

	// not binding
	var $language       = null;
	var $copyright      = null;
	var $managingeditor = null;
	var $webmaster      = null;
	var $pubdate        = null;
	var $lastbuilddate  = null;
	var $category       = null;
	var $generator      = null;
	var $docs           = null;
	var $cloud          = null;
	var $ttl            = null;

	// image
	var $image_exists	= false;
	var $image_url		= null;
	var $image_title	= null;
	var $image_link		= null;
	
	function container_feed($feed_id, $feed_url, $lastindex, $lastbuilddate_int,
    $pubdate_int, $update, $title, $link, $description, $language, $copyright,
    $managingeditor, $webmaster, $pubdate, $lastbuilddate, $category, $generator,
    $docs, $cloud, $ttl, $image_url, $image_title, $image_link)
	{
		$this->feed_id	    		= $feed_id;
		$this->feed_url	     		= $feed_url;
		$this->lastindex	    	= $lastindex;
		$this->lastbuilddate_int    = $lastbuilddate_int;
		$this->pubdate_int  		= $pubdate_int;
		$this->update		      	= $update;

		$this->title	    	  	= $title;
		$this->link                 = $link;
		$this->description          = $description; 
	
		$this->language         = $language;
		$this->copyright		= $copyright;
		$this->managingeditor	= $managingeditor;
		$this->webmaster		= $webmaster;
		$this->pubdate			= $pubdate;
		$this->lastbuilddate	= $lastbuilddate;
		$this->category			= $category;
		$this->generator		= $generator;
		$this->docs				= $docs;
		$this->cloud			= $cloud;
		$this->ttl				= $ttl;
		
        $this->image_exists = $image_exists;
        $this->image_url    = $image_url;
        $this->image_title  = $image_title;
        $this->image_link   = $image_link;
    }
}

