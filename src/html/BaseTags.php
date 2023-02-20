<?php
namespace braga\tools\html;

/**
 * @package common
 * @author Tomasz.Gajewski
 * Created on 2010-06-16 14:24:41
 * klasa gromadząca statyczne metody tworzące tragi HTML
 */
class BaseTags
{
	// -------------------------------------------------------------------------
	public static function custom($tag, $innerHTML, $attributes)
	{
		$pre = trim(preg_replace("/\s+/", " ", $tag . " " . $attributes));
		$retval = "<" . $pre . ">" . $innerHTML . "</" . $tag . ">";
		return $retval;
	}
	// -------------------------------------------------------------------------
	public static function customShort($tag, $attributes)
	{
		$attributes = trim(preg_replace("/\s+/", " ", $attributes));
		$retval = "<" . $tag . " " . $attributes . ">";
		return $retval;
	}
	// -------------------------------------------------------------------------
	public static function customShortXML($tag, $attributes)
	{
		$attributes = trim(preg_replace("/\s+/", " ", $attributes));
		$retval = "<" . $tag . " " . $attributes . " />";
		return $retval;
	}
	// -------------------------------------------------------------------------
	public static function form($innerHTML, $attributes = "method='post' action=''")
	{
		return self::custom("form", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function nav($innerHTML, $attributes = "")
	{
		return self::custom("nav", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function td($innerHTML, $attributes = "")
	{
		return self::custom("td", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function th($innerHTML, $attributes = "")
	{
		return self::custom("th", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function thead($innerHTML, $attributes = "")
	{
		return self::custom("thead", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function tbody($innerHTML, $attributes = "")
	{
		return self::custom("tbody", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function tr($innerHTML, $attributes = "")
	{
		return self::custom("tr", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function table($innerHTML, $attributes = "")
	{
		return self::custom("table", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function span($innerHTML, $attributes = "")
	{
		return self::custom("span", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function i($innerHTML, $attributes = "")
	{
		return self::custom("i", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function p($innerHTML, $attributes = "")
	{
		return self::custom("p", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function div($innerHTML, $attributes = "")
	{
		return self::custom("div", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function hr($attributes = "")
	{
		return self::customShort("hr", $attributes);
	}
	// -------------------------------------------------------------------------
	public static function iframe($innerHTML, $attributes = "")
	{
		return self::custom("iframe", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function script($innerHTML, $attributes = "type='text/javascript'")
	{
		return self::custom("script", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function img($src, $attributes = "alt=''")
	{
		return self::customShort("img", "src='" . $src . "' " . $attributes);
	}
	// -------------------------------------------------------------------------
	public static function fieldset($innerHTML, $attributes = "")
	{
		return self::custom("fieldset", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function option($innerHTML, $attributes = "")
	{
		return self::custom("option", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function legend($innerHTML, $attributes = "")
	{
		return self::custom("legend", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function select($innerHTML, $attributes = "")
	{
		return self::custom("select", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function textarea($innerHTML, $attributes = "")
	{
		return self::custom("textarea", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function input($attributes = "")
	{
		return self::customShort("input", $attributes);
	}
	// -------------------------------------------------------------------------
	public static function sup($innerHTML, $attributes = "")
	{
		return self::custom("sup", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function sub($innerHTML, $attributes = "")
	{
		return self::custom("sub", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function optgroup($innerHTML, $attributes = "")
	{
		return self::custom("optgroup", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function a($innerHTML, $attributes = "")
	{
		return self::custom("a", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function b($innerHTML, $attributes = "")
	{
		return self::custom("b", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function button($innerHTML, $attributes = "")
	{
		return self::custom("button", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function br()
	{
		return self::customShort("br", "");
	}
	// -------------------------------------------------------------------------
	public static function applet($innerHTML, $attributes = "")
	{
		return self::custom("applet", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function pre($innerHTML, $attributes = "")
	{
		return self::custom("pre", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function html($innerHTML, $attributes = "")
	{
		return self::custom("html", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function head($innerHTML, $attributes = "")
	{
		return self::custom("head", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function link($attributes)
	{
		return self::customShort("link", $attributes);
	}
	// -------------------------------------------------------------------------
	public static function label($innerHTML, $attributes = "")
	{
		return self::custom("label", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function li($innerHTML, $attributes = "")
	{
		return self::custom("li", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function ul($innerHTML, $attributes = "")
	{
		return self::custom("ul", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function title($innerHTML, $attributes = "")
	{
		return self::custom("title", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function h1($innerHTML, $attributes = "")
	{
		return self::custom("h1", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function h2($innerHTML, $attributes = "")
	{
		return self::custom("h2", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function h3($innerHTML, $attributes = "")
	{
		return self::custom("h3", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function h4($innerHTML, $attributes = "")
	{
		return self::custom("h4", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function h5($innerHTML, $attributes = "")
	{
		return self::custom("h5", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function h6($innerHTML, $attributes = "")
	{
		return self::custom("h6", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function h7($innerHTML, $attributes = "")
	{
		return self::custom("h7", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function h8($innerHTML, $attributes = "")
	{
		return self::custom("h8", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function body($innerHTML, $attributes = "")
	{
		return self::custom("body", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function code($innerHTML, $attributes = "")
	{
		return self::custom("code", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function strong($innerHTML, $attributes = "")
	{
		return self::custom("strong", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function meta($attributes)
	{
		return self::customShort("meta", $attributes);
	}
	// -------------------------------------------------------------------------
	public static function object($innerHTML, $attributes)
	{
		return self::custom("object", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
	public static function param($attributes)
	{
		return self::customShort("param", $attributes);
	}
	// -------------------------------------------------------------------------
	public static function u($innerHTML, $attributes = "")
	{
		return self::custom("u", $innerHTML, $attributes);
	}
	// -------------------------------------------------------------------------
}
?>