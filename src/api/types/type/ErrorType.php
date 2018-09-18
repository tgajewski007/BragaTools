<?php
namespace braga\tools\api\types\type;
/**
 * Created on 22 lip 2018 16:46:50
 * error prefix
 * @author Tomasz Gajewski
 * @package
 *
 */
class ErrorType
{
	// -----------------------------------------------------------------------------------------------------------------
	public $number;
	public $description;
	// -----------------------------------------------------------------------------------------------------------------
	public static function convertFromThrowrable(\Throwable $e)
	{
		$retval = new self();
		$retval->number = $e->getCode();
		$retval->description = $e->getMessage();
		return $retval;
	}
	// -----------------------------------------------------------------------------------------------------------------
}