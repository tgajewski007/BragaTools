<?php

namespace braga\tools\html;

use braga\tools\tools\Retval;
/**
 * Created 10.03.2025 10:40
 * error prefix
 * @autor Tomasz Gajewski
 */
abstract class SubController
{
	// -----------------------------------------------------------------------------------------------------------------
	public function __construct(protected Controler $parentController)
	{
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return void
	 */
	abstract public function registerActions(): void;
	// -----------------------------------------------------------------------------------------------------------------
}
