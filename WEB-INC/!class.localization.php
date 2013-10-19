<?php
// класс обаботки xml-файла
class localization
{

function readfile_chunked ($filename, $type='array') { 
      $chunk_array=array(); 
      $chunksize = 1*(1024*1024); // how many bytes per chunk 
      $buffer = ''; 
      $handle = fopen($filename, 'rb'); 
      if ($handle === false) { 
       return false; 
      } 
      while (!feof($handle)) { 
          switch($type) 
          { 
              case'array': 
              // Returns Lines Array like file() 
              $lines[] = fgets($handle, $chunksize); 
              break; 
              case'string': 
              // Returns Lines String like file_get_contents() 
              $lines = fread($handle, $chunksize); 
              break; 
          } 
      } 
       fclose($handle); 
       return $lines; 
    } 

	/**
	* Обрработка XML-документа, преобразование в массив
	*
	* Param: str $strXmlDoc
	* Return: array
	*/
	function localization()
	{
		$boolDocument = false;
		$arrRssData = null;

		$filename = './WEB-INC/lang/russian.xml';
		
		   if(!is_file($filename)) {
//      header('HTTP/1.0 404 Not Found');
        return 404;
    }

    if(!is_readable($filename)) {
//      header('HTTP/1.0 403 Forbidden');
        return 403;
    }
	
	 $lines = $this->readfile_chunked ($filename, 'string');
	   
	   
	   
	   
	   
		
		$p = xml_parser_create();
		xml_parse_into_struct($p, $lines, $vals, $index);
		xml_parser_free($p);

		$type = 0;
		$tmp[] = array("", "", "");
		$id = 0;
		
		print_r($vals);
/*
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
						if ($vals[$ci]['tag'] == "WIDTH" && ($vals[$ci]['level'] == ($vals[$i]['level'] + 1))) {
							$image['WIDTH'] = $vals[$ci]['value'];
							$i++;
						}
						if ($vals[$ci]['tag'] == "HEIGHT" && ($vals[$ci]['level'] == ($vals[$i]['level'] + 1))) {
							$image['HEIGHT'] = $vals[$ci]['value'];
							$i++;
						}
					break;
					case "DESCRIPTION" :
						$channel['DESCRIPTION'] = addslashes($vals[$i]['value']);
						break;
					case "CONTENT:ENCODED" :
						$channel['CONTENT:ENCODED'] = $vals[$i]['value'];
					break;
					case "COPYRIGHT" :
						$channel['COPYRIGHT'] = $vals[$i]['value'];
						break;
					case "DC:RIGHTS" :
						$channel['DC:RIGHTS'] = $vals[$i]['value'];
						break;
					break;
					case "MANAGINGEDITOR" :
						$channel['MANAGINGEDITOR'] = $vals[$i]['value'];
						break;
					case "DC:PUBLISHER" :
						$channel['DC:PUBLISHER'] = $vals[$i]['value'];
					break;
					case "DC:DATE" :
						$channel['DC:DATE'] = $vals[$i]['value'];
					break;
					case "LANGUAGE" :
						$channel['LANGUAGE'] = $vals[$i]['value'];
						break; 
				}
			} else {
				switch ($vals[$i]['tag']) {
					case "TITLE":
						$tmp[0] = addslashes($vals[$i]['value']);
					break;
					case "LINK" :
						$tmp[1] = $vals[$i]['value'];
					break;
					case "DESCRIPTION" :
						$tmp[2] = addslashes($vals[$i]['value']);
						break;
					case "CONTENT:ENCODED" :
						$tmp[3] = $vals[$i]['value'];
					break;
					case "PUBDATE" :
						$tmp[4] = date('Ymdhis', strtotime($vals[$i]['value']));
					break;
					case "CATEGORY" :
						$tmp[5] = $vals[$i]['value'];
					break;
					case "COMMENTS" :
						$tmp[6] = $vals[$i]['value'];
					break;
				}
			}

			if ($vals[$i]['tag'] == "ITEM") {
				if (($vals[$i]['type'] == "open") && ($type == 0)) {
					$type = 1;
				}

				if ($vals[$i]['type'] == "close") {
					$items[] = $tmp;
					unset($tmp);
				}
			}
		}

		$arrRssData['CHANNEL'] = $channel;

		if (isset($image)) {
			$arrRssData['IMAGE'] = $image;
		}

		$arrRssData['ITEMS'] = $items;

		return $arrRssData;*/
	} // end function getrss
};
?>