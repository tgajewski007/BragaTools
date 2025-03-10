<?php

namespace braga\tools\html;

use braga\tools\exception\BragaException;
use braga\tools\tools\Retval;
/**
 * Created 10.03.2025 10:40
 * error prefix
 * @autor Tomasz Gajewski
 */
abstract class SubController
{
	// -----------------------------------------------------------------------------------------------------------------
	protected Retval $r;
	// ----------------------------------------------------------------------------------------------------------------
	public function __construct(protected Controler $parentController)
	{
		$this->r = $this->parentController->r;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return void
	 */
	abstract public function registerActions(): void;
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $action
	 * @param callable $fn
	 * @return void
	 * @throws BragaException
	 */
	protected function registerAction(string $action, callable $fn): void
	{
		$this->parentController->registerAction($action, $fn);
	}
	// -----------------------------------------------------------------------------------------------------------------
}
