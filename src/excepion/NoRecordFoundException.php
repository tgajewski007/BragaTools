<?php
namespace braga\tools\excepion;
class NoRecordFoundException extends BragaException
{
	public function __construct($message = null, $code = null, $previous = null)
	{
		parent::__construct($message = null, $code = null, $previous = null);
	}
}

