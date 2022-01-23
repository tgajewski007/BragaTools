<?php
namespace braga\tools\api;
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
		$tmp = parse_url($_SERVER["REQUEST_URI"]);
		$path = isset($tmp["path"]) ? $tmp["path"] : null;

		if(!empty($this->urlPrefix))
		{
			$path = str_replace($this->urlPrefix, "", $path);
		}

		foreach($this->filtr as $f)
		{
			if($f->method == $_SERVER["REQUEST_METHOD"])
			{
				$matches = null;
				$retval = preg_match_all($f->urlRegExp, $path, $matches);
				if($retval)
				{
					$param = $this->cleanMatches($matches);

					if(isset($tmp["query"]))
					{
						$paramQuery = null;
						parse_str($tmp["query"], $paramQuery);
						$param = array_merge($param, $paramQuery);
					}

					$f->function->call($this, $param);
					return;
				}
			}
		}
		$this->loggerClassNama::error("Method not allowed", [
						"\$_SERVER" => json_encode($_SERVER, JSON_PRETTY_PRINT) ]);
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

