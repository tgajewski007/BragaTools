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
	const MESSAGE_INFO = "MI";
	const MESSAGE_WARNING = "MW";
	const MESSAGE_ALERT = "MA";
	const MESSAGE_SQL = "MS";
	// -------------------------------------------------------------------------
	/**
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
			$_COOKIE[$key] = $value;
			return setcookie($key, $value, $lifetime, "/");
		}
		else
		{
			throw new \Exception("BT:10201 Header already sends");
		}
	}
	// -------------------------------------------------------------------------
	public static function kill($key)
	{
		if(isset($_COOKIE[$key]))
		{
			unset($_COOKIE[$key]);
		}
		return setcookie($key, "", time() - 3600, "/");
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
