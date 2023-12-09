<?php

namespace braga\tools\process;

/**
 * Created 09.12.2023 18:27
 * error prefix
 * @autor Tomasz Gajewski
 */
class CallbackStepProcess
{
	// -----------------------------------------------------------------------------------------------------------------
	public function __construct(public bool $proceseed = false)
	{
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function isProceseed(): bool
	{
		return $this->proceseed;
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function setProceseed(bool $proceseed): void
	{
		$this->proceseed = $proceseed;
	}
	// -----------------------------------------------------------------------------------------------------------------
}