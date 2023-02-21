<?php

namespace braga\tools\process;

use braga\tools\process\exception\ProcessFailExecutionException;
/**
 * Created 21.02.2023 18:59
 * error prefix
 * @autor Tomasz Gajewski
 */
abstract class SuccessCallback implements ProcessCallback
{
	// -----------------------------------------------------------------------------------------------------------------
	public function check(): bool
	{
		return true;
	}
	// -----------------------------------------------------------------------------------------------------------------
	final public function fail()
	{
		throw new ProcessFailExecutionException("BR:60001 Funkcja fail nie jest obsługiwana", 60001);
	}
	// -----------------------------------------------------------------------------------------------------------------
}