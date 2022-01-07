<?php
namespace braga\tools\tools;
use braga\tools\exception\NoRecordFoundException;
class RequestUrl
{
	// -----------------------------------------------------------------------------------------------------------------
	private static $url;
	// -----------------------------------------------------------------------------------------------------------------
	public static function get($index)
	{
		if(empty(self::$url))
		{
			$tmp = parse_url($_SERVER["REQUEST_URI"]);
			self::$url = explode("/", $tmp["path"] ?? "");
		}
		if(isset(self::$url[$index]))
		{
			return self::$url[$index];
		}
		else
		{
			throw new NoRecordFoundException("Index not found");
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	public static function toString()
	{
		return $_SERVER["REQUEST_URI"];
	}
	// -----------------------------------------------------------------------------------------------------------------
}
