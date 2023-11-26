<?php
namespace braga\tools\api;
use braga\tools\api\types\response\ErrorResponseType;
use braga\tools\api\types\type\ContentType;
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
	const HTTP_STATUS_202_ACCEPTED = "202 Accepted";
	/**
	 * @deprecated
	 */
	const HTTP_STATUS_402_BUSINES_ERROR = "422 Busines Error";
	const HTTP_STATUS_405_METHOD_NOT_ALLOWED = "405 Method Not Allowed";
	const HTTP_STATUS_406_NOT_ACCEPTABLE = "406 Not Acceptable";
	const HTTP_STATUS_422_BUSINES_ERROR = "422 Busines Error";
	const HTTP_STATUS_500_INTERNAL_ERROR = "500 Error";
	const HTTP_STATUS_403_NOT_AUTHORIZED = "403 Not Authorized";
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $loggerClassNama
	 */
	public function setLoggerClassNama($loggerClassNama): void
	{
		$this->loggerClassNama = $loggerClassNama;
	}
	// -----------------------------------------------------------------------------------------------------------------
	abstract public function doAction();
	// -----------------------------------------------------------------------------------------------------------------
	protected function sendStandardsHeaders(ContentType $contentType)
	{
		$filename = null;
		$linenum = null;
		if(headers_sent($filename, $linenum))
		{
			$this->loggerClassNama::debug("BT:10101 Headers sent f:" . $filename . " l:" . $linenum);
		}
		header("Expires: " . date("c"));
		header("Cache-Control: no-transform; max-age=0; proxy-revalidate; no-cache; must-revalidate; no-store; post-check=0; pre-check=0");
		header("Pragma: no-cache");
		header("Content-Type: " . $contentType->value);
		// send all CORS
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: *");
		header("Access-Control-Allow-Headers: *");
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param mixed $retval
	 * @param string $responseCode
	 * @return void
	 * @deprecated  use sendJson
	 */
	protected function send($retval, $responseCode = self::HTTP_STATUS_200_OK): void
	{
		$this->sendJson($retval, $responseCode);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param mixed $retval
	 * @param string $responseCode
	 * @return void
	 */
	protected function sendPlainText($retval, $responseCode = self::HTTP_STATUS_200_OK): void
	{
		$this->sendBasic($retval, $responseCode, ContentType::PLAIN_TEXT);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param mixed $retval
	 * @param string $responseCode
	 * @return void
	 */
	protected function sendJson($retval, $responseCode = self::HTTP_STATUS_200_OK): void
	{
		$retval = json_encode($retval);
		$this->sendBasic($retval, $responseCode, ContentType::JSON);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param mixed $retval
	 * @param string $responseCode
	 * @return void
	 */
	protected function sendXml($retval, $responseCode = self::HTTP_STATUS_200_OK): void
	{
		$this->sendBasic($retval, $responseCode, ContentType::XML);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param mixed $retval
	 * @param string $responseCode
	 * @return void
	 */
	protected function sendHtml($retval, $responseCode = self::HTTP_STATUS_200_OK): void
	{
		$this->sendBasic($retval, $responseCode, ContentType::HTML);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $retval
	 * @param string $responseCode
	 * @param ContentType $contentType
	 * @return void
	 */
	protected function sendBasic($retval, $responseCode = self::HTTP_STATUS_200_OK, ContentType $contentType = ContentType::HTML): void
	{
		$this->sendStandardsHeaders($contentType);
		header("HTTP/1.0 " . $responseCode);
		self::sendResponse($retval);
		$this->loggerClassNama::notice($_SERVER["REQUEST_URI"] . " Response", array(
			"body"   => $retval,
			"status" => $responseCode ));
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $responseCode
	 * @return void
	 */
	protected function sendMethodNotAllowed($responseCode = self::HTTP_STATUS_405_METHOD_NOT_ALLOWED): void
	{
		$this->sendBasic(null, $responseCode, ContentType::JSON);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param ErrorResponseType $retval
	 * @param string $responseCode
	 * @return void
	 * @deprecated use sendError
	 */
	protected function forwardError(ErrorResponseType $retval, $responseCode = self::HTTP_STATUS_500_INTERNAL_ERROR): void
	{
		$retval = json_encode($retval);
		$this->sendBasic($retval, $responseCode, ContentType::JSON);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param \Throwable $e
	 * @param string $responseCode
	 * @return void
	 */
	protected function sendError(\Throwable $e, $responseCode = self::HTTP_STATUS_500_INTERNAL_ERROR): void
	{
		$retval = new ErrorResponseType();
		$retval->error[] = ErrorType::convertFromThrowrable($e);
		$retval = json_encode($retval);
		$this->sendBasic($retval, $responseCode, ContentType::JSON);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return string
	 */
	protected function getBody(): string
	{
		$retval = file_get_contents('php://input');
		$this->loggerClassNama::info($_SERVER["REQUEST_URI"] . " getBody", array(
			"body" => $retval ));

		return $retval;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param $jsonString
	 * @return mixed
	 * @throws \Exception
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
	 * @template T
	 * @param class-string<T> $className
	 * @return T
	 */
	protected function getBodyObjectMapped($className)
	{
		$jsonString = $this->getBody();
		$retval = JsonSerializer::fromJson($jsonString, $className);
		$this->loggerClassNama::info($_SERVER["REQUEST_URI"] . " bodyObj", array(
			"obj"       => JsonSerializer::toJson($retval),
			"className" => get_class($retval) ));

		return $retval;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @template T
	 * @param class-string<T> $className
	 * @return T[]
	 */
	protected function getBodyArrayMapped($className)
	{
		$jsonString = $this->getBody();
		$retval = JsonSerializer::arrayFromJson($jsonString, $className);
		if(count($retval) > 0)
		{
			$this->loggerClassNama::info($_SERVER["REQUEST_URI"] . " bodyArray", array(
				"obj"       => JsonSerializer::toJson($retval),
				"className" => get_class(current($retval)) ));
		}
		else
		{
			$this->loggerClassNama::info($_SERVER["REQUEST_URI"] . " bodyArray", array(
				"obj" => JsonSerializer::toJson($retval) ));
		}
		return $retval;
	}
	// -----------------------------------------------------------------------------------------------------------------
	public static function sendResponse($response)
	{
		ignore_user_abort(true);
		set_time_limit(0);
		ob_start();
		echo $response;
		header('Connection: close');
		header('Content-Length: ' . ob_get_length());
		ob_end_flush();
		flush();
	}
	// -----------------------------------------------------------------------------------------------------------------
}