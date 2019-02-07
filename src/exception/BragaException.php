<?php
namespace braga\tools\exception;
class BragaException extends \RuntimeException
{
	public function __construct($message = null, $code = null, $previous = null)
	{
		parent::__construct($message = null, $code = null, $previous = null);
	}
}

