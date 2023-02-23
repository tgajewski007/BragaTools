<?php

namespace braga\tools\process;

/**
 * Created 23.02.2023 13:02
 * error prefix
 * @autor Tomasz Gajewski
 */
trait StoreProcessController
{
	// -----------------------------------------------------------------------------------------------------------------
	protected ?ProcessController $processController;
	// -----------------------------------------------------------------------------------------------------------------
	protected function setProcessController(ProcessController $processController)
	{
		$this->processController = $processController;
	}
	// -----------------------------------------------------------------------------------------------------------------
	protected function getProcessController(): ProcessController
	{
		return $this->processController;
	}
	// -----------------------------------------------------------------------------------------------------------------
}