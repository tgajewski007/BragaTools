<?php
namespace braga\tools\tools;
use braga\tools\html\Controler;

/**
 * Created on 17-10-2011 22:01:45
 * @author Tomasz Gajewski
 * @package common
 */
class PostChecker
{
	// -------------------------------------------------------------------------
	private static $instance = null;
	// -------------------------------------------------------------------------
	/**
	 *
	 * @var PostLogger
	 */
	private static $logger = null;
	// -------------------------------------------------------------------------
	public static function setLogger(PostLogger $l)
	{
		self::$logger = $l;
	}
	// -------------------------------------------------------------------------
	public static function get($key)
	{
		if(empty(self::$instance))
		{
			self::setInstance();
		}
		if(isset(self::$instance[$key]))
		{
			return self::$instance[$key];
		}
		else
		{
			return null;
		}
	}
	// -------------------------------------------------------------------------
	public static function set($key, $value)
	{
		self::$instance[$key] = $value;
	}
	// -------------------------------------------------------------------------
	public function checkPost(Controler $controler)
	{
		$this->setInstance();
		if(isset(self::$instance["js"]))
		{
			$controler->js = true;
		}
	}
	// -------------------------------------------------------------------------
	protected static function setInstance()
	{
		$request = self::preCheckVal($_REQUEST, "GET");
		self::$instance = $request;
		if(!empty(self::$logger))
		{
			self::$logger->save(self::$instance);
		}
	}
	// -------------------------------------------------------------------------
	protected static function preCheckVal($array, $argName)
	{
		$retval = array();
		foreach($array as $name => $val)
		{
			$name = strtolower($name);
			$retval[$name] = self::checkVal($val, $argName . "[" . $name . "]");
		}
		return $retval;
	}
	// -------------------------------------------------------------------------
	protected static function checkVal($argumentValue, $argumentName)
	{
		if(!is_array($argumentValue))
		{
			$retval = $argumentValue;
			$retval = preg_replace('/[[:cntrl:]]/', '', $retval);
			$retval = htmlspecialchars($argumentValue, ENT_QUOTES, "UTF-8");
			$retval = trim($retval);
			return $retval;
		}
		else
		{
			$retval = array();
			foreach($argumentValue as $klucz => $wartosc)
			{
				$klucz = strtolower($klucz);
				$retval[$klucz] = self::checkVal($wartosc, $argumentName);
			}
			return $retval;
		}
	}
	// -------------------------------------------------------------------------
}
?>