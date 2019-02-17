<?php
namespace braga\tools\exception;
class BragaException extends \RuntimeException
{
	protected $previous;
	public function __construct($message = null, $code = null, $previous = null)
	{
		parent::__construct($message = null, $code = null, $previous = null);
		$this->message = $message;
		$this->code = $code;
		$this->previous = $previous;
	}
}

