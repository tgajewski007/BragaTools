<?php
use braga\tools\tools\Message;
use braga\tools\tools\SessManager;

/**
 *
 * @author Tomasz.Gajewski
 * @package system
 * Created on 2008-07-14 12:22:24
 * error_prexix EM:903
 * klasa odpowiedzialna za sprawdzanie danych przychodzących z przeglądarki
 */
// =============================================================================
function getmicrotime()
{
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}
// =============================================================================
function addAlert($txt)
{
	Logger::getLogger("braga")->error($txt);
	$m = Message::import(htmlspecialchars($txt, ENT_QUOTES));
	if(!is_null($m))
	{
		SessManager::add(SessManager::MESSAGE_ALERT, $m);
	}
}
// =============================================================================
function addSQLError($txt)
{
	Logger::getLogger("braga")->fatal($txt);
	$m = Message::import(htmlspecialchars($txt, ENT_QUOTES));
	if(!is_null($m))
	{
		SessManager::add(SessManager::MESSAGE_SQL, $m);
	}
}
// =============================================================================
function addMsg($txt)
{
	Logger::getLogger("braga")->info($txt);
	$m = Message::import(htmlspecialchars($txt, ENT_QUOTES));
	if(!is_null($m))
	{
		SessManager::add(SessManager::MESSAGE_INFO, $m);
	}
}
// =============================================================================
function addWarn($txt)
{
	Logger::getLogger("braga")->warn($txt);
	$m = Message::import(htmlspecialchars($txt, ENT_QUOTES));
	if(!is_null($m))
	{
		SessManager::add(SessManager::MESSAGE_WARNING, $m);
	}
}
// =============================================================================
function addDebugInfo($txt)
{
	if(is_object($txt) || is_array($txt))
	{
		$txt = var_export($txt, true);
	}
	Logger::getLogger("braga")->debug($txt);
}
// =============================================================================
function getRandomStringLetterOnly($dlugosc)
{
	$keychars = "abcdefghijklmnopqrstuvwxyz";
	$randkey = "";
	$max = strlen($keychars) - 1;
	for($i = 0; $i < $dlugosc; $i++)
	{
		$randkey .= substr($keychars, rand(0, $max), 1);
	}
	return $randkey;
}
// =============================================================================
function getRandomString($dlugosc)
{
	$keychars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$randkey = "";
	$max = strlen($keychars) - 1;
	for($i = 0; $i < $dlugosc; $i++)
	{
		$randkey .= substr($keychars, rand(0, $max), 1);
	}
	return $randkey;
}
// =============================================================================
function plCharset($string)
{
	$string = mb_strtolower($string);
	$polskie = array(
					',',
					' ',
					' ',
					'ę',
					'Ę',
					'ó',
					'Ó',
					'Ą',
					'ą',
					'Ś',
					's',
					'ł',
					'Ł',
					'ż',
					'Ż',
					'Ź',
					'ź',
					'ć',
					'Ć',
					'ń',
					'Ń',
					'-',
					"'",
					"/",
					"?",
					'"',
					":",
					'ś',
					'!',
					'.',
					'&',
					'&amp;',
					'#',
					';',
					'[',
					']',
					'(',
					')',
					'`',
					'%',
					'”',
					'„',
					'…' );
	$miedzyn = array(
					'-',
					'-',
					'-',
					'e',
					'e',
					'o',
					'o',
					'a',
					'a',
					's',
					's',
					'l',
					'l',
					'z',
					'z',
					'z',
					'z',
					'c',
					'c',
					'n',
					'n',
					'-',
					"",
					"",
					"",
					"",
					"",
					's',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'' );
	$string = str_replace($polskie, $miedzyn, $string);

	// usuń wszytko co jest niedozwolonym znakiem
	$string = preg_replace('/[^0-9a-z\-]+/', '', $string);

	// zredukuj liczbę myślników do jednego obok siebie
	$string = preg_replace('/[\-]+/', '-', $string);

	// usuwamy możliwe myślniki na początku i końcu
	$string = trim($string, '-');

	$string = stripslashes($string);

	// // na wszelki wypadek
	// $string = urlencode($string);

	return $string;
}
// =============================================================================
function formatMonney($kwota)
{
	if(!is_null($kwota))
	{
		return number_format($kwota, 2, ",", " ");
	}
}
// =============================================================================
function formatDate($date)
{
	if(!empty($date))
	{
		return date(PHP_DATE_FORMAT, strtotime($date));
	}
}
// =============================================================================
function formatDateForRaport($date)
{
	if(!empty($date))
	{
		return '="' . $date . '"';
	}
}
// =============================================================================
function formatDateTime($time)
{
	if(!empty($time))
	{
		return date(PHP_DATETIME_FORMAT, strtotime($time));
	}
}
// =============================================================================
function formatBoolean($b)
{
	if($b)
	{
		return "Tak";
	}
	else
	{
		return "Nie";
	}
}
// =============================================================================
function formatBytes($bytes)
{
	if($bytes > 0)
	{
		$unit = intval(log($bytes, 1024));
		$units = array(
						'B',
						'kiB',
						'MiB',
						'GiB' );

		if(array_key_exists($unit, $units) === true)
		{
			return round($bytes / pow(1024, $unit), 1) . " " . $units[$unit];
		}
	}
	return $bytes;
}
// =============================================================================
function formatNrb($numer)
{
	// from http://forum.php.pl/index.php?showtopic=118827
	$suma = substr($numer, 0, 2); // 2 cyfry
	$nr_roz1 = substr($numer, 2, 4); // pierwsze 4 cyfry
	$nr_roz2 = substr($numer, 6, 4); // drugie 4 cyfry
	$nr_rach1 = substr($numer, 10, 4); // pierwsze 4 cyfry
	$nr_rach2 = substr($numer, 14, 4); // drugie 4 cyfry
	$nr_rach3 = substr($numer, 18, 4); // trzecie 4 cyfry
	$nr_rach4 = substr($numer, 22, 4); // czwarte 4 cyfry

	return $suma . ' ' . $nr_roz1 . ' ' . $nr_roz2 . ' ' . $nr_rach1 . ' ' . $nr_rach2 . ' ' . $nr_rach3 . ' ' . $nr_rach4;
}
// =============================================================================
function formatText($text)
{
	return nl2br($text, true);
}
// =============================================================================
function sortByLength($a, $b)
{
	if($a == $b)
		return 0;
	return (mb_strlen($a) > mb_strlen($b) ? 1 : -1);
}
// =============================================================================
function reverseSortByLength($a, $b)
{
	if($a == $b)
		return 0;
	return (mb_strlen($a) > mb_strlen($b) ? -1 : 1);
}
// =============================================================================
function stripUrl($url)
{
	$url = preg_replace("/(https?:\/\/)/i", "", $url);
	return "http://" . $url;
}
// =============================================================================
function getMonthName($nr)
{
	$m = array();
	$m[1] = "Styczeń";
	$m[2] = "Luty";
	$m[3] = "Marzec";
	$m[4] = "Kwiecień";
	$m[5] = "Maj";
	$m[6] = "Czerwiec";
	$m[7] = "Lipiec";
	$m[8] = "Sierpień";
	$m[9] = "Wrzesień";
	$m[10] = "Październik";
	$m[11] = "Listopad";
	$m[12] = "Grudzień";
	return $m[$nr];
}
// =============================================================================
function groupCollection(Iterator $collection, $groupFunctionName)
{
	$retval = array();
	foreach($collection as $key => $value)
	{
		$groupKey = call_user_func(array(
						$value,
						$groupFunctionName ));
		$retval[$groupKey][$key] = $value;
	}
	return $retval;
}
// =============================================================================
function CheckPESEL($str)
{
	if(!preg_match('/^[0-9]{11}$/', $str)) // sprawdzamy czy ciąg ma 11 cyfr
	{
		return false;
	}

	$arrSteps = array(
					1,
					3,
					7,
					9,
					1,
					3,
					7,
					9,
					1,
					3 );
	$intSum = 0;
	for($i = 0; $i < 10; $i++)
	{
		$intSum += $arrSteps[$i] * $str[$i];
	}
	$int = 10 - $intSum % 10;
	$intControlNr = ($int == 10) ? 0 : $int;
	if($intControlNr == $str[10])
	{
		return true;
	}
	return false;
}
// =============================================================================
function checkNIP($str)
{
	$str = preg_replace("/[^0-9]+/", "", $str);
	if(strlen($str) != 10)
	{
		return false;
	}

	$arrSteps = array(
					6,
					5,
					7,
					2,
					3,
					4,
					5,
					6,
					7 );
	$intSum = 0;
	for($i = 0; $i < 9; $i++)
	{
		$intSum += $arrSteps[$i] * $str[$i];
	}
	$int = $intSum % 11;

	$intControlNr = ($int == 10) ? 0 : $int;
	if($intControlNr == $str[9])
	{
		return true;
	}
	return false;
}
// =============================================================================
function checkTime($time)
{
	return preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9])/", $time);
}
// =============================================================================
function checkREGON($str)
{
	if(strlen($str) == 9)
	{
		$arrSteps = array(
						8,
						9,
						2,
						3,
						4,
						5,
						6,
						7 );
		$intSum = 0;
		for($i = 0; $i < 8; $i++)
		{
			$intSum += $arrSteps[$i] * $str[$i];
		}
		$int = $intSum % 11;
		$intControlNr = ($int == 10) ? 0 : $int;
		if($intControlNr == $str[8])
		{
			return true;
		}
		return false;
	}
	elseif(strlen($str) == 14)
	{
		$arrSteps = array(
						2,
						4,
						8,
						5,
						0,
						9,
						7,
						3,
						6,
						1,
						2,
						4,
						8 );
		$intSum = 0;
		for($i = 0; $i < 13; $i++)
		{
			$intSum += $arrSteps[$i] * $str[$i];
		}
		$int = $intSum % 11;
		$intControlNr = ($int == 10) ? 0 : $int;
		if($intControlNr == $str[13])
		{
			return true;
		}
		return false;
	}
	else
	{
		return false;
	}
}
// =============================================================================
function sizeFileFormat($bytes)
{
	if($bytes > 0)
	{
		$unit = intval(log($bytes, 1024));
		$units = array(
						'B',
						'kiB',
						'MiB',
						'GiB' );

		if(array_key_exists($unit, $units) === true)
		{
			return round($bytes / pow(1024, $unit), 1) . " " . $units[$unit];
		}
	}
	return $bytes;
}
// =============================================================================
function getHashPass($pass, $idUzytkownik = null)
{
	return hash(HASH_ALGORYTM, $pass . $idUzytkownik);
}
// =============================================================================
function formatKodPocztowy($kod)
{
	$kod = substr(preg_replace("/[^0-9]+/", "", $kod), 0, 5);
	return substr($kod, 0, 2) . "-" . substr($kod, 2, 3);
}
// =============================================================================
function isEmail($email)
{
	return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
}
// -----------------------------------------------------------------------------
function isMobile($mobile)
{
	if(!preg_match("/^[5-8]{1}[0-9]{8}$/", $mobile))
	{
		return false;
	}
	$pref = (int)substr($mobile, 0, 3);
	// źródło: http://www.uke.gov.pl/tablice/NumerPlmn-list.do?execution=e1s1
	if($pref < 500)
	{
		return false;
	}
	if($pref >= 520 && $pref <= 529)
	{
		return false;
	}
	if($pref >= 540 && $pref <= 569)
	{
		return false;
	}
	if($pref >= 580 && $pref <= 599)
	{
		return false;
	}
	if($pref >= 610 && $pref <= 659)
	{
		return false;
	}
	if($pref >= 700 && $pref <= 719)
	{
		return false;
	}
	if($pref >= 740 && $pref <= 779)
	{
		return false;
	}
	if($pref >= 800 && $pref <= 879)
	{
		return false;
	}
	if($pref >= 890)
	{
		return false;
	}
	return true;
}
// =============================================================================
function addMonth($date, $countOfMonth)
{
	$day = date("d", $date);
	$month = date("m", $date);
	$year = date("Y", $date);
	$dateOut = mktime(0, 0, 0, $month + $countOfMonth, $day, $year);

	$monthOut = date("m", $dateOut);
	$yearOut = date("Y", $dateOut);

	$m = ($month + $countOfMonth) % 12;
	if($m == 0)
	{
		$m = 12;
	}
	if($m != $monthOut)
	{
		if($year != $yearOut)
		{
			$lastDayOfMonth = cal_days_in_month(CAL_GREGORIAN, $m, $yearOut);
			$dateOut = mktime(0, 0, 0, $m, $lastDayOfMonth, $yearOut);
		}
		else
		{
			$lastDayOfMonth = cal_days_in_month(CAL_GREGORIAN, $month + $countOfMonth, $year);
			$dateOut = mktime(0, 0, 0, $month + $countOfMonth, $lastDayOfMonth, $year);
		}
	}

	return date(PHP_DATE_FORMAT, $dateOut);
}
// =============================================================================
function roundFloor($number, $precision = 2, $separator = '.')
{
	$numberpart = explode($separator, $number);
	if(isset($numberpart[1]))
	{
		$numberpart[1] = substr_replace($numberpart[1], $separator, $precision, 0);
		if($numberpart[0] >= 0)
		{
			$numberpart[1] = substr(floor('1' . $numberpart[1]), 1);
		}
		else
		{
			$numberpart[1] = substr(ceil('1' . $numberpart[1]), 1);
		}
		$ceil_number = array(
						$numberpart[0],
						$numberpart[1] );
		return (float)implode($separator, $ceil_number);
	}
	else
	{
		return $number;
	}
}
// =============================================================================
function cleanVariableForLikeParam($var)
{
	$var = str_replace("%", "", $var);
	$var = explode(" ", $var);
	$var = current($var);
	return $var;
}
// =============================================================================
function cleanFullTextSearch($search)
{
	$search = preg_replace('/[^\p{L}\p{N}_]+/u', ' ', $search);
	$search = preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $search);
	return $search;
}
// =============================================================================
?>