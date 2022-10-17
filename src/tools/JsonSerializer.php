<?php
namespace braga\tools\tools;
class JsonSerializer
{
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $jsonString
	 * @param string $className
	 * @return \stdClass
	 */
	public static function fromJson($jsonString, $className)
	{
		$json = json_decode($jsonString);
		if(empty($json))
		{
			$obj = new $className();
		}
		else
		{
			$mapper = new \JsonMapper();
			$mapper->bStrictNullTypes = false;
			$obj = $mapper->map($json, new $className());
		}
		return $obj;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $jsonString
	 * @param string $className
	 * @return array
	 */
	public static function arrayFromJson($jsonString, $className)
	{
		$json = json_decode($jsonString);
		if(empty($json))
		{
			$obj = array();
		}
		else
		{
			$mapper = new \JsonMapper();
			$mapper->bStrictNullTypes = false;
			$obj = $mapper->mapArray($json, array(), $className);
		}
		return $obj;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param mixed $obj
	 * @return string
	 */
	public static function toJson($obj)
	{
		return json_encode($obj, JSON_PRETTY_PRINT);
	}
	// -----------------------------------------------------------------------------------------------------------------
}
