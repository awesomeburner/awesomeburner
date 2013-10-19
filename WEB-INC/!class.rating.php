<?php 
define('GMAG', 0xE6359A60); 

class rating
{
	function nooverflow($a) 
	{ 
		while ($a < -2147483648) {
			$a += 2147483648 + 2147483648;
		} 

		while ($a > 2147483647) {
			$a -= 2147483648 + 2147483648;
		} 

		return $a; 
	} 

	function zeroFill($x, $bits) 
	{
		if ($bits == 0) {
			return $x; 
		}

		if ($bits == 32) {
			return 0; 
		}

		$y = ($x & 0x7FFFFFFF) >> $bits; 

		if (0x80000000 & $x) { 
			$y |= (1<<(31 - $bits)); 
		} 

		return $y; 
	} 

	function mix($a,$b,$c)
	{ 
		$a = (int) $a;
		$b = (int) $b;
		$c = (int) $c; 
		
		$a -= $b;
		$a -= $c;
		$a = $this->nooverflow($a);
		$a ^= ($this->zeroFill($c, 13)); 

		$b -= $c;
		$b -= $a;
		$b = $this->nooverflow($b);
		$b ^= ($a << 8); 

		$c -= $a;
		$c -= $b;
		$c = $this->nooverflow($c);
		$c ^= ($this->zeroFill($b, 13)); 

		$a -= $b;
		$a -= $c;
		$a = $this->nooverflow($a);
		$a ^= ($this->zeroFill($c, 12)); 

		$b -= $c;
		$b -= $a;
		$b = $this->nooverflow($b);
		$b ^= ($a << 16); 

		$c -= $a;
		$c -= $b;
		$c = $this->nooverflow($c);
		$c ^= ($this->zeroFill($b, 5)); 

		$a -= $b;
		$a -= $c;
		$a = $this->nooverflow($a);
		$a ^= ($this->zeroFill($c, 3)); 

		$b -= $c;
		$b -= $a;
		$b = $this->nooverflow($b);
		$b ^= ($a << 10); 

		$c -= $a;
		$c -= $b;
		$c = $this->nooverflow($c);
		$c ^= ($this->zeroFill($b, 15)); 

		return array($a,$b,$c); 
	}

	function GCH($url, $length=null, $init=GMAG) { 
    	if(is_null($length)) {
			$length = sizeof($url); 
		} 

		$a = $b = 0x9E3779B9;
		$c = $init;
		$k = 0;
		$len = $length; 

		while($len >= 12) { 
			$a += ($url[$k + 0] + ($url[$k + 1] << 8) + ($url[$k + 2] << 16) + ($url[$k + 3] << 24));
			$b += ($url[$k + 4] + ($url[$k + 5] << 8) + ($url[$k + 6] << 16) + ($url[$k + 7] << 24));
			$c += ($url[$k + 8] + ($url[$k + 9] << 8) + ($url[$k + 10] << 16) + ($url[$k + 11] << 24));
			$mix = $this->mix($a, $b, $c);
			$a = $mix[0];
			$b = $mix[1];
			$c = $mix[2];
			$k += 12;
			$len -= 12; 
		} 

		$c += $length; 

		switch ($len) {
			case 11:
				$c += ($url[$k + 10] << 24);
			case 10:
				$c += ($url[$k + 9] << 16); 
			case 9 :
				$c += ($url[$k + 8] << 8); 
			case 8 :
				$b += ($url[$k + 7] << 24); 
			case 7 :
				$b += ($url[$k + 6] << 16); 
			case 6 :
				$b += ($url[$k + 5] << 8); 
			case 5 :
				$b += ($url[$k + 4]); 
			case 4 :
				$a += ($url[$k + 3] << 24); 
			case 3 :
				$a += ($url[$k + 2] << 16); 
			case 2 :
				$a += ($url[$k + 1] << 8); 
			case 1 :
				$a += ($url[$k + 0]); 
		} 

		$mix = $this->mix($a, $b, $c); 

		return $mix[2]; 
	} 

	function strord($string) 
	{ 
		for($i = 0; $i < strlen($string); ++$i) 
		{ 
			$result[$i] = ord($string{$i}); 
		} 

		return $result; 
	}


	function getPageRank($aUrl) 
	{ 
	  $url = 'info:'.$aUrl; 
	  $ch = GCH(strord($url)); 
	  $url='info:'.urlencode($aUrl); 
	  $pr = @file("http://www.google.com/search?client=navclient-auto&ch=6{$ch}&ie=UTF-8&oe=UTF-8&features=Rank&q={$url}"); 
	  $pr_str = @implode("", $pr); 
	
	  return substr($pr_str, strrpos($pr_str, ":") + 1); 
	}

	function yandex_tic($url)
	{
		$file = file_get_contents("http://search.yaca.yandex.ru/yca/cy/ch/{$url}/");

		if (preg_match("!&#151;\s+([0-9]{0,8})<\/b>!is", $file, $ok))
		{
			// сайт не в каталоге.
			$str = $ok[1];
		} else if(preg_match("!<td class=\"current\" valign=\"middle\">(.*?)</td>\n</tr>!si", $file, $ok)) {
			if (preg_match("!<td align=\"right\">(.*?)</td>\n</tr>!si", $ok[0], $str)) {
				// сайт в каталоге.
				$str = $str[1];
			} else {
				$str=0;
			}
		} else {
			$str = 0;
		}

		return trim($str);
	}

	function getBarCY($_url)
	{
		$_uri = "http://bar-navig.yandex.ru/u?ver=2&url=".urlencode("http://".$_url)."&show=1";
		$fd = @fopen($_uri, "r");

		if ($fd) {
			while ($buffer = fgets($fd, 4096)) {
				$haystack .= $buffer;
			}

			fclose($fd);
			preg_match("/<tcy rang=\"(.*)\" value=\"(.*)\"\/>/isU", $haystack, $cy);

			return (int) $cy[2];
		} else {
			return 0;
		}
	}
};

// Пример использования:

echo ">>>".getBarCY("zhilinsky.ru"); // покажет значение Яндекс.тИЦ, полученное из Яндекс.Бара.
echo ">>>".yandex_tic("zhilinsky.ru"); // покажет значени Яндекс.тИЦ, полученное из Яндекс.Каталога.
echo ">>>".getPageRank("zhilinsky.ru"); // покажет значение Google PageRank.
?>