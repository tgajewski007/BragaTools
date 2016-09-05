<?php
/**
 *
 * @author Tomasz.Gajewski
 * @package system
 * Created on 2008-07-14 12:22:24
 * error_prexix EM:903
 * klasa odpowiedzialna za sprawdzanie danych przychodzących z przeglądarki
 */
// =============================================================================
function WebHitelGetMsg()
{
	$retval = "";
	if(isset($_SESSION["info"]))
	{
		foreach($_SESSION["info"] as $value) /* @var $value Message */
		{
			$retval .= Tags::h3($value->getNumer() . " " . $value->getOpis());
		}
		unset($_SESSION["info"]);
	}

	if(isset($_SESSION["warning"]))
	{

		foreach($_SESSION["warning"] as $value)/* @var $value Message */
		{
			$retval .= Tags::h3($value->getNumer() . " " . $value->getOpis());
		}
		unset($_SESSION["warning"]);
	}

	if(isset($_SESSION["alert"]))
	{
		foreach($_SESSION["alert"] as $value)/* @var $value Message */
		{
			$retval .= Tags::h3($value->getNumer() . " " . $value->getOpis());
		}
		unset($_SESSION["alert"]);
	}

	if(isset($_SESSION["sqlError"]))
	{
		foreach($_SESSION["sqlError"] as $value)/* @var $value Message */
		{
			$retval .= Tags::h3($value->getOpis());
		}
		unset($_SESSION["sqlError"]);
	}
	return $retval;
}
// =============================================================================
function GetMsg()
{
	$retval = "";
	if(isset($_SESSION["info"]))
	{

		$wiadomosci = "";
		foreach($_SESSION["info"] as $value)
		{
			$id = "MSG" . getRandomString(5);
			$wiadomosci .= Tags::p($value->getOpis(), "class='clear' id='" . $id . "'");
			$wiadomosci .= Tags::script("$(\"#" . $id . "\").parent().parent().delay(5000).hide(\"slide\",{direction: \"up\"})");
		}
		unset($_SESSION["info"]);
		$title = icon("ui-icon-notice");
		$title .= "Info";
		$title .= Tags::span("", "class='ui-icon ui-icon-close hand' style='float:right;' onclick='\$(this).parent().parent().remove(); HideToolTip(); return false;' " . ToolTip("Zamknij"));
		$title = Tags::div($title, "class='ui-widget-header ui-corner-all ui-helper-clearfix' style='padding:2px'");
		$content = Tags::div($wiadomosci, "class='ui-corner-bottom ui-priority-primary clear' style='padding:8px'");
		$tmp = $title . $content;
		$retval .= Tags::div($tmp, " style='width:auto;margin-bottom:4px;padding:2px' class='ui-widget-content ui-state-highlight ui-corner-all'");
	}

	if(isset($_SESSION["warning"]))
	{

		$wiadomosci = "";
		foreach($_SESSION["warning"] as $value)
		{
			$id = "MSG" . getRandomString(5);
			$wiadomosci .= Tags::p(Tags::span(Tags::i($value->getNumer()), "style='float:right;font-size:75%;'") . $value->getOpis(), "class='clear' id='" . $id . "'");
			$wiadomosci .= Tags::script("$(\"#" . $id . "\").parent().parent().delay(5000).hide(\"slide\",{direction: \"up\"})");
		}
		unset($_SESSION["warning"]);
		$title = icon("ui-icon-info");
		$title .= "Ostrzeżenie";
		$title .= Tags::span("", "class='ui-icon ui-icon-close hand' style='float:right;' onclick='\$(this).parent().parent().remove(); HideToolTip(); return false;' " . ToolTip("Zamknij"));
		$title = Tags::div($title, "class='ui-widget-header ui-corner-all ui-helper-clearfix ui-state-error' style='padding:2px'");
		$content = Tags::div($wiadomosci, "class='ui-corner-bottom ui-priority-primary clear' style='padding:8px'");
		$tmp = $title . $content;
		$retval .= Tags::div($tmp, " style='width:auto;margin-bottom:4px;padding:2px' class='ui-widget-content ui-state-highlight ui-corner-all'");
	}

	if(isset($_SESSION["alert"]))
	{
		$wiadomosci = "";
		foreach($_SESSION["alert"] as $value)
		{
			$id = "MSG" . getRandomString(5);
			$wiadomosci .= Tags::p(Tags::span(Tags::i($value->getNumer()), "style='float:right;font-size:75%;'") . $value->getOpis(), "class='clear' id='" . $id . "'");
			$wiadomosci .= Tags::script("$(\"#" . $id . "\").parent().parent().delay(10000).hide(\"slide\",{direction: \"up\"})");
		}
		unset($_SESSION["alert"]);
		$title = icon("ui-icon-alert");
		$title .= "Alert";
		$title .= Tags::span("", "class='ui-icon ui-icon-close hand' style='float:right;' onclick='\$(this).parent().parent().remove(); HideToolTip(); return false;' " . ToolTip("Zamknij"));
		$title = Tags::div($title, "class='ui-widget-header ui-corner-all ui-helper-clearfix ui-state-highlight' style='padding:2px'");
		$content = Tags::div($wiadomosci, "class='ui-corner-bottom ui-priority-primary clear' style='padding:8px'");
		$tmp = $title . $content;
		$retval .= Tags::div($tmp, " style='margin-bottom:4px;padding:2px' class='ui-widget-content ui-state-error ui-corner-all'");
	}

	if(isset($_SESSION["sqlError"]))
	{
		$wiadomosci = "";
		foreach($_SESSION["sqlError"] as $value)
		{
			$wiadomosci .= Tags::p($value->getOpis());
		}
		unset($_SESSION["sqlError"]);

		$title = icon("ui-icon-alert");
		$title .= "SQLError";
		$title .= Tags::span("", "class='ui-icon ui-icon-close hand' style='float:right;' onclick='\$(this).parent().parent().remove(); HideToolTip(); return false;' " . ToolTip("Zamknij"));
		$title = Tags::div($title, "class='ui-widget-header ui-corner-all ui-helper-clearfix ui-state-highlight' style='padding:2px'");
		$content = Tags::div($wiadomosci, "class='ui-corner-bottom ui-priority-primary clear' style='padding:8px'");
		$tmp = $title . $content;
		$retval .= Tags::div($tmp, " style='margin-bottom:4px;padding:2px' class='ui-widget-content ui-state-error ui-corner-all'");
	}
	return $retval;
}
// =============================================================================
function getmicrotime()
{
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}
// =============================================================================
function addAlert($text)
{
	$m = Message::import(htmlspecialchars($text, ENT_QUOTES));
	if(!is_null($m))
	{
		$_SESSION["alert"][] = $m;
	}
	addErrorLog($text);
}
// =============================================================================
function addSQLError($text)
{
	$m = Message::import($text);
	if(!is_null($m))
	{
		$_SESSION["sqlError"][] = $m;
	}
}
// =============================================================================
function addMsg($text)
{
	$m = Message::import(htmlspecialchars($text, ENT_QUOTES));
	if(!is_null($m))
	{
		$_SESSION["info"][] = $m;
	}
}
// =============================================================================
function addWarn($text)
{
	$m = Message::import(htmlspecialchars($text, ENT_QUOTES));
	if(!is_null($m))
	{
		$_SESSION["warning"][] = $m;
	}
}
// =============================================================================
function addErrorLog($text)
{
	if(is_object($text) || is_array($text))
	{
		$text = var_export($text, true);
	}
	if(Uzytkownik::getCurrent() instanceof Uzytkownik)
	{
		$idUzytkownik = Uzytkownik::getCurrent()->getIdUzytkownik();
	}
	else
	{
		$idUzytkownik = 0;
	}

	$retval = date("Y-m-d H:i:s") . "," . $idUzytkownik . "," . $text . "\r\n";
	$filename = INSTALL_DIRECTORY . "log/Error." . date(PHP_DATE_FORMAT) . ".log";
	file_put_contents($filename, $retval, FILE_APPEND);
}
// =============================================================================
function addDebugInfo($text)
{
	if(is_object($text) || is_array($text))
	{
		$text = var_export($text, true);
	}
	$retval = date("Y-m-d H:i:s") . "," . $text;
	$h = fopen(INSTALL_DIRECTORY . "log/Debug.log", "a");
	fwrite($h, $retval, mb_strlen($retval));
	fwrite($h, "\r\n", 2);
	fclose($h);
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
			'…');
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
			'');
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
	return number_format($kwota, 2, ",", " ");
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
				'GiB');

		if(array_key_exists($unit, $units) === true)
		{
			return round($bytes / pow(1024, $unit), 1) . " " . $units[$unit];
		}
	}
	return $bytes;
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
function icon($icon = "", $float = "left")
{
	return Tags::span("", "class='ui-icon " . $icon . "' style='float:" . $float . "'");
}
// =============================================================================
function groupCollection(Iterator $collection, $groupFunctionName)
{
	$retval = array();
	foreach($collection as $key => $value)
	{
		$groupKey = call_user_func(array(
				$value,
				$groupFunctionName));
		$retval[$groupKey][$key] = $value;
	}
	return $retval;
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
			7);
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
				7);
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
				8);
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
				'GiB');

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
	return hash(HASH_ALGORYTM, $pass);
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
	return (bool)(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email));
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
?>