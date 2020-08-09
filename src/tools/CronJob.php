<?php

namespace braga\tools\tools;

/**
 * Created on 20-07-2011 11:35:11
 * @author Tomasz.Gajewski
 * @package cron
 * error prefix
 */
abstract class CronJob
{
	// -----------------------------------------------------------------------------------------------------------------
	abstract public function run();
	// -----------------------------------------------------------------------------------------------------------------
}
