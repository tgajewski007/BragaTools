<?php
namespace braga\tools\api;
class ApiFiltr
{
	// -----------------------------------------------------------------------------------------------------------------
	const GET = "GET";
	const HEAD = "HEAD";
	const POST = "POST";
	const PUT = "PUT";
	const DELETE = "DELETE";
	const CONNECT = "CONNECT";
	const OPTIONS = "OPTIONS";
	const TRACE = "TRACE";
	const PATCH = "PATCH";
	const ANY = "*";
	// -----------------------------------------------------------------------------------------------------------------
	public string $method;
	public string $urlRegExp;
	public \Closure $function;
	// -----------------------------------------------------------------------------------------------------------------
	function __construct(string $method, string $url, \Closure $function)
	{
		$this->method = $method;
		$this->urlRegExp = $this->convertToRegExp($url);
		$this->function = $function;
	}
	// -----------------------------------------------------------------------------------------------------------------
	private function convertToRegExp($url)
	{
		// parametry
		$subject = $url;
		$replacement = "(.*)";
		$pattern = "/({)(.*?)(})/";
		$retval = preg_replace($pattern, $replacement, $subject);

		// slashe
		$retval = str_replace("/", "\/", $retval);

		// początek i koniec
		$retval = "/^" . $retval . "$/";
		return $retval;
	}
	// -----------------------------------------------------------------------------------------------------------------
}

