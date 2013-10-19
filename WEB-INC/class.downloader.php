<?php
class downloader {
    /**
     * Имя агента. Если установлен 'random', имя браузера будет выбрано по рандому.
     *
     * @var string
     */
    var $userAgent = 'BeeblogBot (http://beeblog.org/)';
	var $timeout = 30;

	// if you need http auth
    //var $username = null;
    //var $password = null;
 
    //var $proxy = ''; // 'ip:port'
    //var $referer = 'http://www.google.com/';
	
	/**
     * if you want to cache your requests you need to create folder APP/tmp/cache/browser
     *
     * @var string
     */
    //var $cacheFolder = null;
	
	function downloader() {
		$this->_initUserAgent();
	}
	
	/**
	* Закачивает RSS из сети
	*
	* Param: $strUrl
	* Return: string
	*/
	function get_resource($strUrl) {
		$ch = curl_init();    

		curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
		curl_setopt($ch, CURLOPT_URL, $strUrl);
		//curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/xml"));
		//curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Accept: application/xml"));
	
		//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		//curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		//curl_setopt($ch, CURLOPT_POST, TRUE);  
	
		$data = curl_exec($ch);
		
		
		if (DEBUG_MODE==true) {
			echo "\nLoaded: ".strlen($data)."b\n";
		}
		
		
		curl_close($ch);
	
	
	/*
	        curl_setopt($this->handle, CURLOPT_PROXY, $this->proxy);
        curl_setopt($this->handle, CURLOPT_REFERER, $this->referer);
        curl_setopt($this->handle, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($this->handle, CURLOPT_URL, str_replace('&amp;','&',$url));
        curl_setopt($this->handle, CURLOPT_HEADER, 1);
        curl_setopt($this->handle, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->handle, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($this->handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->handle, CURLOPT_SSL_VERIFYHOST,  2);
 
        curl_setopt($this->handle, CURLOPT_COOKIEJAR, APP.'tmp/cookie.txt');
        curl_setopt($this->handle, CURLOPT_COOKIEFILE, APP.'tmp/cookie.txt');
 
        if (!empty($postvars)){
            curl_setopt($this->handle, CURLOPT_POST, 1);
            curl_setopt($this->handle, CURLOPT_POSTFIELDS, $postvars);
        }
 
        if (!empty($this->username)) {
            curl_setopt($this->handle, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($this->handle, CURLOPT_USERPWD, $this->username.':'.$this->password); // $auth should be [username]:[password]
        }
	*/
	
	
		return $data;
	}
	
	
    /**
     * Эмуляция UserAgent
     *
     * @return string browser name
     */
    function _initUserAgent() {
        if ($this->userAgent!='random') return true;
 
        $browsers = array(
            'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows 98)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.0.3705)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 1.1.4322)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Avant Browser; .NET CLR 2.0.50727)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 9.10',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; FunWebProducts)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; MRA 4.8 (build 01709); Maxthon; .NET CLR 1.1.4322; .NET CLR 2.0.50727)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; ru) Opera 8.50',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; ru) Opera 8.54',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0; .NET CLR 2.0.50727)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; MAXTHON 2.0)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727; .NET CLR 1.1.4322; InfoPath.1)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; InfoPath.2; .NET CLR 1.1.4322; MAXTHON 2.0)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; InfoPath.1)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; InfoPath.2)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRA 4.7 (build 01670); .NET CLR 1.1.4322)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRA 4.7 (build 01670); InfoPath.1)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRA 4.8 (build 01709))',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRA 4.8 (build 01709); .NET CLR 1.1.4322)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRA 4.8 (build 01709); .NET CLR 2.0.50727; InfoPath.2; .NET CLR 1.1.4322; .NET CLR 3.0.04506.30)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRA 4.8 (build 01709); Maxthon; .NET CLR 2.0.50727; .NET CLR 1.1.4322; .NET CLR 3.0.04506.30)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MyIE2; InfoPath.1)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MyIE2; MRA 4.8 (build 01709); .NET CLR 1.1.4322; InfoPath.1)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; .NET CLR 1.1.4322)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 1.1.4322; InfoPath.1)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Avant Browser; Avant Browser; .NET CLR 1.1.4322)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Maxthon)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Maxthon; Avant Browser; InfoPath.2)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Maxthon; MyIE2; .NET CLR 1.0.3705; .NET CLR 2.0.50727; InfoPath.2)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; MRA 4.6 (build 01425); InfoPath.1)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; MRA 4.8 (build 01709))',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; MRA 4.8 (build 01709); .NET CLR 1.1.4322; InfoPath.1)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; MRA 4.8 (build 01709); Avant Browser)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; MRA 4.9 (build 01863); .NET CLR 2.0.50727)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; MyIE2)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; MyIE2; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 1.1.4322; MEGAUPLOAD 1.0)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506; InfoPath.2; .NET CLR 1.1.4322)',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; bg; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.8.0.11) Gecko/20070312 Firefox/1.5.0.11',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.8.1.4) Gecko/20070515 Firefox/2.0.0.4',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.4) Gecko/20070515 Firefox/2.0.0.4',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.0.11) Gecko/20070312 Firefox/1.5.0.11',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.1.4) Gecko/20070515 Firefox/2.0.0.4',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU; rv:1.7.12) Gecko/20050919 Firefox/1.0.7',
            'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.1) Gecko/20060313 Fedora/1.5.0.1-9 Firefox/1.5.0.1 pango-text',
            'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.8) Gecko/20060112 ASPLinux/1.5-1.2am Firefox/1.5',
            'Opera/8.54 (Windows NT 5.1; U; en)',
            'Opera/9.00 (Windows NT 5.1; U; ru)',
            'Opera/9.01 (Windows NT 5.1; U; ru)',
            'Opera/9.02 (Windows NT 5.0; U; ru)',
            'Opera/9.02 (Windows NT 5.1; U; ru)',
            'Opera/9.02 (Windows NT 5.2; U; en)',
            'Opera/9.10 (Windows NT 5.1; U; ru)',
            'Opera/9.20 (Windows NT 5.1; U; en)',
            'Opera/9.20 (Windows NT 5.1; U; ru)',
            'Opera/9.21 (Windows NT 5.1; U; ru)',
            );
 
        $this->userAgent = $browsers[array_rand($browsers)];
        return true;
    }
}
