<?php
namespace braga\tools\tools;
/**
 * Created on 12 sty 2014 17:35:45
 * error prefix
 * @author Tomasz Gajewski
 * @package frontoffice
 */
class CookieManager
{
	// -------------------------------------------------------------------------
	/**
	 *
	 * @var int
	 * @staticvar 30*24*60*60
	 */
	const LIFETIME = 2592000;
	// -------------------------------------------------------------------------
	public static function set($key, $value, $lifetime = self::LIFETIME)
	{
		if(!headers_sent())
		{
			if($lifetime < 0)
			{
				$lifetime = 0;
			}
			else
			{
				$lifetime = time() + $lifetime;
			}
			return setcookie($key, $value, $lifetime, "/", null, true);
		}
		else
		{
			throw new \Exception("BT:10101 Header already sends");
		}
	}
	// -------------------------------------------------------------------------
	public static function kill($key)
	{
		return setcookie($key, null, 0, "/");
	}
	// -------------------------------------------------------------------------
	public static function get($key)
	{
		if(isset($_COOKIE[$key]))
		{
			return $_COOKIE[$key];
		}
		else
		{
			return null;
		}
	}
	// -------------------------------------------------------------------------
	public static function isExist($key)
	{
		return isset($_COOKIE[$key]);
	}
	// -------------------------------------------------------------------------
}
?>