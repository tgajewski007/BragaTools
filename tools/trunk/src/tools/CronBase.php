<?php
namespace braga\tools\tools;

/**
 * Created on 20-07-2011 11:35:11
 * @author Tomasz.Gajewski
 * @package cron
 * error prefix
 */
abstract class CronBase
{
	// -------------------------------------------------------------------------
	abstract public function go();
	// -------------------------------------------------------------------------
	protected function addDebugLog($text)
	{
		\Logger::getLogger("cron")->debug($text);
	}
	// -------------------------------------------------------------------------
	protected function addErrorLog($text)
	{
		\Logger::getLogger("cron")->error($text);
	}
	// -------------------------------------------------------------------------
	protected function addInfoLog($text)
	{
		\Logger::getLogger("cron")->info($text);
	}
	// -------------------------------------------------------------------------
	protected function addWarningLog($text)
	{
		\Logger::getLogger("cron")->warn($text);
	}
	// -------------------------------------------------------------------------
	protected function addFatalLog($text)
	{
		\Logger::getLogger("cron")->fatal($text);
	}
	// -------------------------------------------------------------------------
	protected function addTraceLog($text)
	{
		\Logger::getLogger("cron")->trace($text);
	}
	// -------------------------------------------------------------------------
}
?>