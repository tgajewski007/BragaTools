<?php

namespace braga\tools\html;

use braga\tools\api\types\type\ContentType;
use braga\tools\benchmark\Benchmark;
/**
 * Created 05.01.2023 11:21
 * error prefix
 * @autor Tomasz Gajewski
 */
class BinaryContent
{
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * Metoda wysyła binarną zawartość z określonym Content-Type
	 */
	public static function send($filename, $content, string $contentType = "application/x-download")
	{
		Benchmark::add(__METHOD__);
		self::sendHeader($filename, strlen($content), $contentType);
		echo $content;
		flush();
		Benchmark::add(__METHOD__ . "_END");
		exit();
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * Metoda wysyła nagłówki niezbędne do pobrania pliku
	 */
	private static function sendHeader($filename, $size, $contentType)
	{
		Benchmark::add(__METHOD__);
		header("Expires: " . date("c"));
		header("Cache-Control: no-transform; max-age=0; proxy-revalidate; no-cache; must-revalidate; no-store; post-check=0; pre-check=0");
		header("Pragma: public");
		header("Content-Disposition: attachment; filename=\"" .  $filename . "\"");
		header("Content-Type: " . $contentType);
		header('Content-Length: ' . $size);
		header('Connection: close');
	}
	// -----------------------------------------------------------------------------------------------------------------
}