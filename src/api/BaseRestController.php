<?php
namespace braga\tools\api;
use braga\tools\api\types\response\ErrorResponseType;
use braga\tools\api\types\type\ErrorType;
use braga\tools\html\Controler;
use braga\graylogger\BaseLogger;
use braga\tools\tools\JsonSerializer;

/**
 * Created on 26 lut 2018 17:49:06
 * error prefix OD:200
 * @author Tomasz Gajewski
 * @package
 *
 */
abstract class BaseRestController
{
	/**
	 * @var BaseLogger
	 */
	protected $loggerClassNama = BaseLogger::class;
	// -----------------------------------------------------------------------------------------------------------------
	const HTTP_STATUS_200_OK = "200 OK";
	const HTTP_STATUS_402_BUSINES_ERROR = "422 Busines Error";
	const HTTP_STATUS_405_METHOD_NOT_ALLOWED = "405 Method Not Allowed";
	const HTTP_STATUS_500_INTERNAL_ERROR = "500 Error";
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $loggerClassNama
	 */
	public function setLoggerClassNama($loggerClassNama)
	{
		$this->loggerClassNama = $loggerClassNama;
	}
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
	protected function send($retval, $responseCode = self::HTTP_STATUS_200_OK)
	{
		$this->sendStandardsHeaders();
		$retval = json_encode($retval);
		header("HTTP/1.0 " . $responseCode);
		Controler::sendResponse($retval);
		$this->loggerClassNama::notice($_SERVER["REQUEST_URI"] . " Response", array(
						"body" => $retval,
						"status" => $responseCode ));
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param object $retval
	 */
	protected function sendMethodNotAllowed($responseCode = self::HTTP_STATUS_405_METHOD_NOT_ALLOWED)
	{
		$this->sendStandardsHeaders();
		header("HTTP/1.0 " . $responseCode);
		Controler::sendResponse(null);
		$this->loggerClassNama::alert($_SERVER["REQUEST_URI"] . " Response", array(
						"status" => $responseCode ));
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param ErrorResponseType $e
	 */
	protected function forwardError(ErrorResponseType $retval, $responseCode = self::HTTP_STATUS_500_INTERNAL_ERROR)
	{
		$this->sendStandardsHeaders();
		header("HTTP/1.0 " . $responseCode);
		$retval = json_encode($retval);
		Controler::sendResponse($retval);
		$this->loggerClassNama::alert($_SERVER["REQUEST_URI"] . " Response", array(
						"body" => $retval,
						"status" => $responseCode ));
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param \Throwable $e
	 */
	protected function sendError(\Throwable $e, $responseCode = self::HTTP_STATUS_500_INTERNAL_ERROR)
	{
		$retval = new ErrorResponseType();
		$retval->error = array(
						ErrorType::convertFromThrowrable($e) );
		$retval = json_encode($retval);

		$this->sendStandardsHeaders();
		header("HTTP/1.0 " . $responseCode);
		Controler::sendResponse($retval);
		$this->loggerClassNama::alert($_SERVER["REQUEST_URI"] . " Response", array(
						"body" => $retval,
						"trace" => $e->getTraceAsString(),
						"status" => $responseCode ));
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return string
	 */
	protected function getBody()
	{
		$retval = file_get_contents('php://input');
		$this->loggerClassNama::info($_SERVER["REQUEST_URI"] . " getBody", array(
						"body" => $retval ));

		return $retval;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $jsonString
	 * @throws \Exception
	 * @return object
	 * @deprecated use getBodyObjectMapped or getBodyArrayMapped
	 */
	protected function importFromJSON($jsonString)
	{
		if(empty($jsonString))
		{
			$this->loggerClassNama::alert($_SERVER["REQUEST_URI"] . " importFromJson(pusty string)", array());
			throw new \Exception("BT:10101 Nie przekazano poprawnej struktury danych JSON");
		}
		else
		{
			$retval = json_decode(html_entity_decode($jsonString, ENT_QUOTES));
			if(empty($retval))
			{
				$retval = json_decode($jsonString);
				if(empty($retval))
				{
					$this->loggerClassNama::alert($_SERVER["REQUEST_URI"] . " Błąd parsowania", array(
									"string" => $jsonString ));
					throw new \Exception("BT:10102 Błąd parsowania danych wejściowych");
				}
			}
			return $retval;
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $className
	 * @return \stdClass
	 */
	protected function getBodyObjectMapped($className)
	{
		$jsonString = $this->getBody();
		$retval = JsonSerializer::fromJson($jsonString, $className);
		$this->loggerClassNama::info($_SERVER["REQUEST_URI"] . " bodyObj", array(
						"obj" => json_encode($retval, JSON_PRETTY_PRINT),
						"className" => get_class($retval) ));

		return $retval;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $className
	 * @return \stdClass[]
	 */
	protected function getBodyArrayMapped($className)
	{
		$jsonString = $this->getBody();
		$retval = JsonSerializer::arrayFromJson($jsonString, $className);
		if(count($retval) > 0)
		{
			$this->loggerClassNama::info($_SERVER["REQUEST_URI"] . " bodyArray", array(
							"obj" => json_encode($retval, JSON_PRETTY_PRINT),
							"className" => get_class(current($retval)) ));
		}
		else
		{
			$this->loggerClassNama::info($_SERVER["REQUEST_URI"] . " bodyArray", array(
							"obj" => json_encode($retval, JSON_PRETTY_PRINT) ));
		}
		return $retval;
	}
	// -----------------------------------------------------------------------------------------------------------------
}