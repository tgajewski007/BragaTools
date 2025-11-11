<?php

namespace braga\tools\api;

use braga\graylogger\BaseLogger;
use braga\tools\api\types\response\ErrorResponseType;
use braga\tools\exception\BragaException;
use braga\tools\exception\BusinesException;
use braga\tools\tools\JsonSerializer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
/**
 * Created 04.02.2024 13:13
 * error prefix BT:110
 * @autor Tomasz Gajewski
 */
abstract class RestClient
{
	// -----------------------------------------------------------------------------------------------------------------
	const MAX_LOGGED_BODY_SIZE = 2048;
	// -----------------------------------------------------------------------------------------------------------------
	protected Client $client;
	protected ?ResponseInterface $response = null;
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $baseUrl
	 * @param BaseLogger $loggerClassNama
	 */
	public function __construct(protected string $baseUrl, protected string $loggerClassNama = BaseLogger::class)
	{
		$this->client = new Client();
	}
	// -----------------------------------------------------------------------------------------------------------------
	abstract protected function getHeaders();
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $url
	 * @param mixed $body
	 * @return ResponseInterface
	 */
	protected function post($url, $body)
	{
		$options = array();
		$options["headers"] = $this->getHeaders();
		if(!is_null($body))
		{
			$options["body"] = JsonSerializer::toJson($body);
			$this->logRequest($this->baseUrl . $url, $options["body"]);
		}
		try
		{
			$this->response = $this->client->post($this->baseUrl . $url, $options);
			$this->logResponse($this->baseUrl . $url, $this->response);
		}
		catch(BadResponseException $e)
		{
			$this->response = $e->getResponse();
			$this->logResponse($this->baseUrl . $url, $this->response, Level::Error);
		}
		return $this->response;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $url
	 * @param mixed $body
	 * @return ResponseInterface
	 */
	protected function put($url, $body)
	{
		$options = array();
		$options["headers"] = $this->getHeaders();
		if(!is_null($body))
		{
			$options["body"] = JsonSerializer::toJson($body);
			$this->logRequest($this->baseUrl . $url, $options["body"]);
		}
		try
		{
			$this->response = $this->client->put($this->baseUrl . $url, $options);
			$this->logResponse($this->baseUrl . $url, $this->response);
		}
		catch(BadResponseException $e)
		{
			$this->response = $e->getResponse();
			$this->logResponse($this->baseUrl . $url, $this->response, Level::Error);
		}
		return $this->response;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $url
	 * @return ResponseInterface
	 */
	protected function get($url)
	{
		$options = array();
		$options["headers"] = $this->getHeaders();
		$this->logRequest($this->baseUrl . $url, null);
		try
		{
			$this->response = $this->client->get($this->baseUrl . $url, $options);
			$this->logResponse($this->baseUrl . $url, $this->response);
		}
		catch(BadResponseException $e)
		{
			$this->response = $e->getResponse();
			$this->logResponse($this->baseUrl . $url, $this->response, Level::Error);
		}
		return $this->response;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $url
	 * @return ResponseInterface
	 */
	protected function delete($url)
	{
		$options = array();
		$options["headers"] = $this->getHeaders();
		$this->logRequest($this->baseUrl . $url, null);
		try
		{
			$this->response = $this->client->delete($this->baseUrl . $url, $options);
			$this->logResponse($this->baseUrl . $url, $this->response);
		}
		catch(BadResponseException $e)
		{
			$this->response = $e->getResponse();
			$this->logResponse($this->baseUrl . $url, $this->response, Level::Error);
		}
		return $this->response;
	}
	// -----------------------------------------------------------------------------------------------------------------
	protected function logRequest($url, $body)
	{
		$context = array();
		if(mb_strlen($body) < self::MAX_LOGGED_BODY_SIZE)
		{
			$context["body"] = $body;
		}
		else
		{
			$context["body"] = mb_substr($body, 0, self::MAX_LOGGED_BODY_SIZE) . "...";
		}
		$context["class"] = static::class;
		$this->loggerClassNama::info($url, $context);
	}
	// -----------------------------------------------------------------------------------------------------------------
	protected function logResponse($url, ResponseInterface $res, $level = Level::Info)
	{
		$context = array();
		$body = $res->getBody()->getContents();
		if(mb_strlen($body) < self::MAX_LOGGED_BODY_SIZE)
		{
			$context["body"] = $body;
		}
		else
		{
			$context["body"] = mb_substr($body, 0, self::MAX_LOGGED_BODY_SIZE) . "...";
		}
		$context["class"] = static::class;
		$context["status"] = strval($res->getStatusCode());
		$this->loggerClassNama::log($level, $url . " Response: " . $res->getStatusCode(), $context);
		$res->getBody()->rewind();
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @template T
	 * @param ResponseInterface $res
	 * @param class-string<T> $class
	 * @param number $successCode
	 * @return T
	 * @throws BragaException
	 */
	protected function inteprete(ResponseInterface $res, $class, $successCode = 200)
	{
		$json = $res->getBody()->getContents();
		if(empty($json) && !empty($class))
		{
			throw new BragaException("BT:11001 Pusta odpowiedż: " . $res->getStatusCode() . " " . $res->getReasonPhrase(), 11001);
		}
		if($res->getStatusCode() == $successCode)
		{
			if(empty($class))
			{
				return null;
			}
			else
			{
				return JsonSerializer::fromJson($json, $class);
			}
		}
		else
		{
			$this->throwBusinesException($json);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @template T
	 * @param ResponseInterface $res
	 * @param class-string<T> $class
	 * @param number $successCode
	 * @return T[]
	 * @throws BusinesException
	 */
	protected function intepreteArray(ResponseInterface $res, $class, $successCode = 200)
	{
		$json = $res->getBody()->getContents();
		if(empty($json) && !empty($class))
		{
			throw new BusinesException("BT:11002 Pusta odpowiedż: " . $res->getStatusCode() . " " . $res->getReasonPhrase(), 11002);
		}
		if($res->getStatusCode() == $successCode)
		{
			return JsonSerializer::arrayFromJson($json, $class);
		}
		else
		{
			$this->throwBusinesException($json);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	protected function intepreteBin(ResponseInterface $res, $successCode = 200)
	{
		$json = $res->getBody()->getContents();
		if(empty($json))
		{
			throw new BusinesException("BT:11003 Pusta odpowiedż: " . $res->getStatusCode() . " " . $res->getReasonPhrase(), 11003);
		}
		if($res->getStatusCode() == $successCode)
		{
			return $json;
		}
		else
		{
			$this->throwBusinesException($res->getBody()->getContents());
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	protected function throwBusinesException($responseContent)
	{
		$resError = JsonSerializer::fromJson($responseContent, ErrorResponseType::class);
		if(count($resError->error) > 0)
		{
			$err = reset($resError->error);
			preg_match('/\d+/', $err->number ?? $err->description, $matches);
			throw new BusinesException($err->number . " " . $err->description, $matches[0] ?? "-1");
		}
		else
		{
			throw new BragaException("BT:11003 Błąd: brak szczegółów :(", 11003);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------

}