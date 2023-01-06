<?php
namespace braga\tools\api\types\type;
use Throwable;
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
	public ?int $number = null;
	public ?string $description = null;
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param Throwable $e
	 * @return ErrorType
	 */
	public static function convertFromThrowrable(Throwable $e): ErrorType
	{
		$retval = new self();
		$retval->number = $e->getCode();
		$retval->description = $e->getMessage();
		return $retval;
	}
	// -----------------------------------------------------------------------------------------------------------------
}