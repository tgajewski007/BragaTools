<?php
namespace braga\tools\tools;
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
function ListGroupItemExtended($title = "Tytuł", $content = "")
{
	$rnd = "LGE" . getRandomStringLetterOnly(8);
	$retval = "<p class='ui-corner-all ui-state-default ui-helper-clearfix' style='padding:0px 2px;' >";
	$retval .= "<span class='hand' OnClick='\$(\"#" . $rnd . "\").slideToggle(\"fast\");";
	$retval .= "\$(this).parent().toggleClass(\"ui-state-active\");";
	$retval .= "\$(\$(this).children(\"span:first-child\")).toggleClass(\"ui-icon-triangle-1-e\");";
	$retval .= "\$(\$(this).children(\"span:first-child\")).toggleClass(\"ui-icon-triangle-1-se\") ' >";
	$retval .= icon("ui-icon-triangle-1-e", "left");
	$retval .= "</span>";
	$retval .= $title;
	$retval .= "</p>";
	$retval .= "<div class='h' id='" . $rnd . "' style='padding-left:4px;'>" . $content . "</div>";

	return $retval;
}
// =============================================================================
function ListGroupItem($title = "Tytuł", $content = "")
{
	$rnd = "LGI" . getRandomStringLetterOnly(8);
	$atrib = "onclick='\$(\"#" . $rnd . "\").slideToggle(\"fast\");\$(this).toggleClass(\"ui-state-active\");\$(\$(this).children(\"span:first-child\")).toggleClass(\"ui-icon-triangle-1-e\");\$(\$(this).children(\"span:first-child\")).toggleClass(\"ui-icon-triangle-1-se\");' ";
	$atrib .= "class='ui-corner-all ui-state-default ui-helper-clearfix hand' ";
	$atrib .= "style='padding:0px 2px;'";
	$retval = Tags::p(icon("ui-icon-triangle-1-e") . $title, $atrib);
	$retval .= Tags::div($content, "class='h' id='" . $rnd . "' style='padding-left:4px;'");
	return $retval;
}
// =============================================================================
function ListGroupItemAjaxExtended($title, $href, $contentId)
{
	$retval = Tags::p(Tags::a(icon("ui-icon-triangle-1-e"), "href='" . $href . "' onclick='" . "if(\$(\"#" . $contentId . "\").is(\":visible\")) {\$(\"#" . $contentId . "\").slideToggle(\"fast\");}\$(this).parent().toggleClass(\"ui-state-active\");\$(this).children(\"span:first-child\").toggleClass(\"ui-icon-triangle-1-e\");\$(this).children(\"span:first-child\").toggleClass(\"ui-icon-triangle-1-se\");if(\$(this).children(\"span:first-child\").hasClass(\"ui-icon-triangle-1-se\")){return fieldAjax.go(this);}else{return false;}'") . $title, "class='ui-corner-all ui-state-default ui-helper-clearfix' style='padding:0px 2px;'");
	$retval .= Tags::div("", "class='h' id='" . $contentId . "' style='padding-left:4px;'");
	return $retval;
}
// =============================================================================
function ListGroupItemAjax($title, $href, $contentId)
{
	$retval = Tags::p(Tags::a(icon("ui-icon-triangle-1-e") . $title, "href='" . $href . "' onclick='" . "if(\$(\"#" . $contentId . "\").is(\":visible\")) " . "{\$(\"#" . $contentId . "\").slideToggle(\"fast\");}" . "\$(this).parent().toggleClass(\"ui-state-active\");" . "\$(this).children(\"span:first-child\").toggleClass(\"ui-icon-triangle-1-e\");" . "\$(this).children(\"span:first-child\").toggleClass(\"ui-icon-triangle-1-se\"); " . "if(\$(this).children(\"span:first-child\").hasClass(\"ui-icon-triangle-1-se\")) " . "{return fieldAjax.go(this);}" . "else" . "{return false;}'"), "class='ui-corner-all ui-state-default ui-helper-clearfix' style='padding:0px 2px;'") . Tags::div("", "class='h' id='" . $contentId . "' style='padding-left:4px;'");
	return $retval;
}
// =============================================================================
function ListItem($content = "", $defaultClass = "ui-state-default")
{
	return Tags::p($content, "class='" . $defaultClass . " ui-corner-all ListItem' style='margin:1px 0px;padding:1px' onclick='\$(\"p.ListItem\").removeClass(\"ui-state-highlight\") ;\$(this).addClass(\"ui-state-highlight\");' onmouseover='\$(this).addClass(\"ui-state-focus\")' onmouseout='\$(this).removeClass(\"ui-state-focus\")'");
}
// =============================================================================
function Fieldset($title = "", $content = "", $display = true, $wyroznienie = false)
{
	$rnd = getRandomStringLetterOnly(8);
	if($title != "")
	{
		$attrib = "class='hand' onclick='\$(\"#" . $rnd . "\").slideToggle(\"fast\");\$(this).children(\"span:first-child\").toggleClass(\"ui-icon-triangle-1-e\");\$(this).children(\"span:first-child\").toggleClass(\"ui-icon-triangle-1-s\") '";
		if($display)
		{
			$legend = Tags::span(icon("ui-icon-triangle-1-s"), $attrib) . $title;
			$hidden = "";
		}
		else
		{
			$legend = Tags::span(icon("ui-icon-triangle-1-e"), $attrib) . $title;
			$hidden = "style='display:none'";
		}

		if($wyroznienie)
		{
			$addClassFieldSet = "ui-state-active";
			$addClassLegend = "ui-state-highlight";
		}
		else
		{

			$addClassFieldSet = "";
			$addClassLegend = "";
		}

		$retval = Tags::div($content, "id='" . $rnd . "' " . $hidden . "");
		$retval .= Tags::legend($legend, "class='ui-widget-header ui-corner-all " . $addClassLegend . "' style='padding:2px 8px;'");
		$retval = Tags::fieldset($retval, "class='ui-widget-content ui-corner-all " . $addClassFieldSet . "' style='margin:4px;'");
	}
	else
	{

		$retval = Tags::div($content, "id='" . $rnd . "'");
		$retval = Tags::fieldset($retval, "class='ui-widget-content ui-corner-all " . $addClassFieldSet . "' style='margin:4px;'");
	}
	return $retval;
}
// =============================================================================
function Box($title = "NoTitle", $content = "", $display = true)
{
	$rnd = getRandomStringLetterOnly(8);
	if($display)
	{
		$retval = Tags::span(icon("ui-icon-triangle-1-s"), "class='hand' onclick='\$(\"#" . $rnd . "\").slideToggle(\"fast\");\$(this).children(\"span:first-child\").toggleClass(\"ui-icon-triangle-1-e\");\$(this).children(\"span:first-child\").toggleClass(\"ui-icon-triangle-1-s\") '");
		$retval .= $title;
		$retval = Tags::div($retval, "class='ui-widget-header ui-corner-all' style='padding:2px'");
		$retval .= Tags::div($content, "id='" . $rnd . "' style='padding:2px;'");
	}
	else
	{
		$retval = Tags::span(icon("ui-icon-triangle-1-e"), "class='hand' onclick='\$(\"#" . $rnd . "\").slideToggle(\"fast\");\$(this).children(\"span:first-child\").toggleClass(\"ui-icon-triangle-1-e\");\$(this).children(\"span:first-child\").toggleClass(\"ui-icon-triangle-1-s\") '");
		$retval .= $title;
		$retval = Tags::div($retval, "class='ui-widget-header ui-corner-all' style='padding:2px'");
		$retval .= Tags::div($content, "id='" . $rnd . "' class='h' style='padding:2px;'");
	}
	$retval = Tags::div($retval, "style='width:auto;margin-bottom:4px;padding:2px' class='ui-widget-content ui-corner-all'");
	return $retval;
}
// =============================================================================
function LongDesc($text = "")
{
	return Tags::p($text, "class='ui-widget-content ui-corner-all ui-state-highlight j' style='padding:4px;margin-bottom:4px;margin-top:2px;'");
}
// =============================================================================
function LongDescError($text = "")
{
	return Tags::p(icon("ui-icon-alert") . $text, "class='ui-widget-content ui-corner-all ui-state-error j b' style='padding:4px;margin-bottom:4px;margin-top:2px;'");
}
// =============================================================================
function CommandButton($caption = "NoTitle", $onClick = "")
{
	return Tags::button($caption, "onclick='" . $onClick . "' style='margin:4px;padding:2px' onmouseover='\$(this).addClass(\"ui-state-hover\")' onmouseout='\$(this).removeClass(\"ui-state-hover\")' class='hand ui-button ui-state-default ui-corner-all'");
}
// =============================================================================
function SubmitButton($label = "Wyślij")
{
	return Tags::input("type='submit' style='margin:4px;padding:2px' onmouseover='\$(this).addClass(\"ui-state-hover\")' onmouseout='\$(this).removeClass(\"ui-state-hover\")' class='ui-button ui-state-default ui-corner-all hand' value='" . $label . "'");
}
// =============================================================================
function PasswordField($name = "no_name", $value = "", $required = false)
{
	$txt = new TextField();
	$txt->setName($name);
	$txt->setRequired($required);
	$txt->setSelected($value);
	$txt->setType("password");
	$retval = $txt->out();

	return $retval;
}
// =============================================================================
function FormRow($desc = "", $real = "")
{
	$retval = Tags::div($desc, "class='FormCellDesc'");
	$retval .= Tags::div($real, "class='FormCellReal'");
	return $retval;
}
// =============================================================================
function FormSubmitRow($real = "")
{
	return Tags::p($real, "class='FormCellReal c '");
}
// =============================================================================
function FileField($name = "no_name")
{
	return Tags::input("type='file' id='" . $name . "' name='" . $name . "'");
}
// =============================================================================
function HiddenField($name = "no_name", $value = "")
{
	return Tags::input("type='hidden' id='" . $name . "' name='" . $name . "' value='" . $value . "'");
}
// =============================================================================
function CheckBoxField($name = "no_name", $checked = false)
{
	$f = new CheckBoxField();
	$f->setName($name);
	$f->setSelected($checked);
	return $f->out();
}
// =============================================================================
function IntegerField($name = "no_name", $value = "", $required = true)
{
	$f = new IntegerField();
	$f->setName($name);
	$f->setSelected($value);
	$f->setRequired($required);

	return $f->out();
}
// =============================================================================
function DateField($name = "no_name", $value = "", $required = false)
{
	$d = new DateField();
	$d->setName($name);
	$d->setRequired($required);
	$d->setSelected($value);
	return $d->out();
}
// =============================================================================
function TimeField($name = "no_name", $value = "", $required = false)
{
	$t = new TimeField();
	$t->setName($name);
	$t->setRequired($required);
	$t->setSelected($value);
	return $t->out();
}
// =============================================================================
function FloatField($name = "no_name", $value = "", $required = true)
{
	$f = new FloatField();
	$f->setName($name);
	$f->setSelected($value);
	$f->setRequired($required);
	return $f->out();
}
// =============================================================================
function EmailField($name = "no_name", $value = "", $required = false, $tabOrder = null)
{
	$txt = new TextField();
	$txt->setName($name);
	$txt->setRequired($required);
	$txt->setSelected($value);
	$txt->setOnBlur("CheckEmail(this," . ($required ? "true" : "false") . ");");
	$txt->setTabOrder($tabOrder);
	$retval = $txt->out();
	return $retval;
}
// =============================================================================
function TextField($name = "no_name", $value = "", $required = false, $multiline = false, $maxLenght = 255, $tabOrder = 0)
{
	if($multiline)
	{
		$txt = new MemoField();
		$txt->setName($name);
		$txt->setTabOrder($tabOrder);
		$txt->setMaxLength($maxLenght);
		$txt->setRequired($required);
		$txt->setSelected($value);
		$retval = $txt->out();
	}
	else
	{
		$txt = new TextField();
		$txt->setName($name);
		$txt->setTabOrder($tabOrder);
		$txt->setMaxLength($maxLenght);
		$txt->setRequired($required);
		$txt->setSelected($value);
		$retval = $txt->out();
	}
	return $retval;
}
// =============================================================================
function enableTinyMCE()
{
	return Tags::script("initTinyMCE();");
}
// =============================================================================
function ToolTip($komunikat)
{
	$komunikat = str_replace("\n", Tags::br(), $komunikat);
	$komunikat = str_replace("\r", "", $komunikat);
	$komunikat = str_replace("\"", "", $komunikat);
	$komunikat = str_replace("'", "", $komunikat);
	return "onmouseover='ToolTip(\"" . $komunikat . "\",event,this)'";
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