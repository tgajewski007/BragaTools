<?php
namespace braga\tools\api\types\response;
use braga\tools\api\types\type\ErrorType;
/**
 * Created on 22 lip 2018 18:20:37
 * error prefix
 * @author Tomasz Gajewski
 * @package
 *
 */
class ErrorResponseType
{
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var ErrorType[]
	 */
	public array $error = [];
	// -----------------------------------------------------------------------------------------------------------------
}