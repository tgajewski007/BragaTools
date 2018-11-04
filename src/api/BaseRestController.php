<?php
namespace braga\tools\api;
use braga\tools\api\types\response\ErrorResponseType;
use braga\tools\api\types\type\ErrorType;
use braga\tools\html\Controler;

/**
 * Created on 26 lut 2018 17:49:06
 * error prefix OD:200
 * @author Tomasz Gajewski
 * @package
 *
 */
abstract class BaseRestController
{
	// -----------------------------------------------------------------------------------------------------------------
	abstract public function doAction();
	// -----------------------------------------------------------------------------------------------------------------
	protected function sendStandardsHeaders()
	{
		header("Expires: " . date("c"));
		header("Cache-Control: no-transform; max-age=0; proxy-revalidate ");
		header("Cache-Control: no-cache; must-revalidate; no-store; post-check=0; pre-check=0 ");
		header("Pragma: no-cache");
		header('Content-Type: application/json; charset=UTF-8');
		header("Access-Control-Allow-Origin: *", true);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param object $retval
	 */
	protected function send($retval)
	{
		$this->sendStandardsHeaders();
		$retval = json_encode($retval);
		Controler::sendResponse($retval);
		\Logger::getLogger("http")->trace("RES: " . $retval);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param object $retval
	 */
	protected function sendMethodNotAllowed()
	{
		$this->sendStandardsHeaders();
		header("HTTP/1.0 405 Method Not Allowed");
		Controler::sendResponse(null);
		\Logger::getLogger("http")->trace("RES:ERR:405 Method Not Allowed");
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param ErrorResponseType $e
	 */
	protected function forwardError(ErrorResponseType $retval)
	{
		$this->sendStandardsHeaders();
		header("HTTP/1.0 500 Error");
		Controler::sendResponse($retval);
		\Logger::getLogger("http")->trace("RES:ERR:500 " . $retval);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param \Throwable $e
	 */
	protected function sendError(\Throwable $e)
	{
		$retval = new ErrorResponseType();
		$retval->error = array(
						ErrorType::convertFromThrowrable($e) );
		$retval = json_encode($retval);

		$this->sendStandardsHeaders();
		header("HTTP/1.0 500 Error");
		Controler::sendResponse($retval);
		\Logger::getLogger("http")->trace("RES:ERR:500 " . $retval);
	}
	// -----------------------------------------------------------------------------------------------------------------
	protected function getBody()
	{
		return file_get_contents('php://input');
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $jsonString
	 * @throws \Exception
	 * @return object
	 */
	protected function importFromJSON($jsonString)
	{
		\Logger::getLogger("http")->trace("REQ: " . $jsonString);
		if(empty($jsonString))
		{
			\Logger::getLogger("http")->error("Błąd parsowania: " . $jsonString);
			throw new \Exception("BT:10101 Nie przekazano poprawnej struktury danych JSON");
		}
		else
		{
			$retval = html_entity_decode($jsonString, ENT_QUOTES);
			$retval = json_decode($retval);
			return $retval;
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
}