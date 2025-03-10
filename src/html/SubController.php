<?php

namespace braga\tools\html;

/**
 * Created 10.03.2025 10:40
 * error prefix
 * @autor Tomasz Gajewski
 */
interface SubController
{
	/**
	 * @param callable[] $actions
	 * @return void
	 */
	public function registerActions(array $actions): void;
}
