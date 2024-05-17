<?php
namespace braga\tools\tools;
use JsonMapper;
class JsonSerializer
{
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @template T
	 * @param string|null $jsonString
	 * @param class-string<T> $className
	 * @return T
	 */
	public static function fromJson(?string $jsonString, string $className)
	{
		if(!empty($jsonString))
		{
			$json = json_decode($jsonString);
			if(empty($json))
			{
				$obj = new $className();
			}
			else
			{
				$mapper = new JsonMapper();
				$mapper->bStrictNullTypes = false;
				$obj = $mapper->map($json, new $className());
			}
			return $obj;
		}
		else
		{
			return new $className;
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @template T
	 * @param string $jsonString
	 * @param class-string<T> $className
	 * @return T[]
	 */
	public static function arrayFromJson(?string $jsonString, string $className): array
	{
		if(!empty($jsonString))
		{
			$json = json_decode($jsonString);
			if(empty($json))
			{
				$obj = [];
			}
			else
			{
				$mapper = new JsonMapper();
				$mapper->bStrictNullTypes = false;
				$obj = $mapper->mapArray($json, array(), $className);
			}
			return $obj;
		}
		else
		{
			return [];
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param mixed $obj
	 * @return string
	 */
	public static function toJson($obj): string
	{
		return json_encode($obj, JSON_PRETTY_PRINT);
	}
	// -----------------------------------------------------------------------------------------------------------------
}
