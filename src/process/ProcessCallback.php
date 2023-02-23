<?php

namespace braga\tools\process;

use braga\tools\process\exception\ProcessException;
/**
 * Created 21.02.2023 18:37
 * error prefix
 * @autor Tomasz Gajewski
 */
interface ProcessCallback
{
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param ProcessController $processController
	 * @return void
	 * @throws ProcessException
	 */
	public function call(ProcessController $processController): void;
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param ProcessException $throwable
	 * @param ProcessController $processController
	 * @return void
	 */
	public function fail(ProcessException $throwable, ProcessController $processController): void;
	// -----------------------------------------------------------------------------------------------------------------
}