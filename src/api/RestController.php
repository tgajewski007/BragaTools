<?php
namespace braga\tools\api;
use braga\tools\benchmark\Benchmark;
use braga\tools\tools\JsonSerializer;
class RestController extends BaseRestController
{
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var string
	 */
	protected $urlPrefix = "";
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var ApiFiltr[]
	 */
	protected $filtr = [];
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $urlPrefix
	 */
	public function setUrlPrefix($urlPrefix)
	{
		$this->urlPrefix = $urlPrefix;
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function addApiFiltr(ApiFiltr $a)
	{
		$this->filtr[] = $a;
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function doAction()
	{
		Benchmark::add(__METHOD__);
		$tmp = parse_url($_SERVER["REQUEST_URI"]);
		$path = isset($tmp["path"]) ? $tmp["path"] : null;

		if(!empty($this->urlPrefix))
		{
			$regExp = str_replace("/", "\/", $this->urlPrefix);
			$path = preg_replace("/^" . $regExp . "/", "", $path);
		}

		foreach($this->filtr as $f)
		{
			if($f->method == $_SERVER["REQUEST_METHOD"] || $f->method == ApiFiltr::ANY)
			{
				$matches = null;
				$retval = preg_match_all($f->urlRegExp, $path, $matches);
				if($retval)
				{
					$param = $this->cleanMatches($matches);

					if(isset($tmp["query"]))
					{
						$paramQuery = [];
						parse_str($tmp["query"], $paramQuery);
						$param = array_merge($param, $paramQuery);
					}
					$this->loggerClassNama::info("Call: " . $f->method . " " . $f->urlRegExp, ["params" => $param, "paramQuery" => $paramQuery, "REQUEST_METHOD" => $_SERVER["REQUEST_METHOD"], "REQUEST_URI" => $_SERVER["REQUEST_URI"], "parsedUri" => JsonSerializer::toJson($this)]);
					$f->function->call($this, $param);
					return;
				}
			}
		}
		$this->loggerClassNama::error(self::HTTP_STATUS_405_METHOD_NOT_ALLOWED, ["REQUEST_METHOD" => $_SERVER["REQUEST_METHOD"], "REQUEST_URI" => $_SERVER["REQUEST_URI"], "parsedUri" => JsonSerializer::toJson($this)]);
		$this->sendMethodNotAllowed();
	}
	// -----------------------------------------------------------------------------------------------------------------
	private function cleanMatches($param)
	{
		$retval = array();
		unset($param[0]);
		foreach($param as $p)
		{
			$retval = array_merge($retval, $p);
		}
		return $retval;
	}
	// -----------------------------------------------------------------------------------------------------------------
}

