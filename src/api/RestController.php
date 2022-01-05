<?php
namespace braga\tools\api;
class RestController extends BaseRestController
{
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var ApiFiltr[]
	 */
	protected $filtr;
	// -----------------------------------------------------------------------------------------------------------------
	public function doAction()
	{
		foreach($this->filtr as $f)
		{
			if($f->method == $_SERVER["REQUEST_METHOD"])
			{
				$matches = null;
				$retval = preg_match_all($f->urlRegExp, $_SERVER["REQUEST_URI"], $matches);
				if($retval)
				{
					$param = $this->cleanMatches($matches);
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

