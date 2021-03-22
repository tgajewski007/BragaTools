<?php
namespace braga\tools\tools;
class JsonSerializer
{
	// -----------------------------------------------------------------------------------------------------------------
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
	public static function toJson($obj, $className)
	{
		if($obj instanceof $className)
		{
			return json_encode($obj, JSON_PRETTY_PRINT);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
}
