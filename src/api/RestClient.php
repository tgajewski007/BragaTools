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
	protected Client $client;
	protected ?ResponseInterface $response = null;
	// -----------------------------------------------------------------------------------------------------------------
	public function __construct(protected string $baseUrl, protected string $loggerClassNama = BaseLogger::class, protected string $responseErrorClassNama = ErrorResponseType::class)
	{
		$this->client = new Client();
	}
	// -----------------------------------------------------------------------------------------------------------------
	abstract protected function getAuthHeaders();
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $url
	 * @param mixed $body
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	protected function post($url, $body)
	{
		$options = array();
		$options["headers"] = $this->getAuthHeaders();
		if(!is_null($body))
		{
			$options["body"] = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
			$this->logRequest($url, $options["body"]);
		}
		try
		{
			$this->response = $this->client->post($this->baseUrl . $url, $options);
			$this->logResponse($url, $this->response);
		}
		catch(BadResponseException $e)
		{
			$this->response = $e->getResponse();
			$this->logResponse($url, $this->response, Level::Error);
		}
		return $this->response;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $url
	 * @param mixed $body
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	protected function put($url, $body)
	{
		$options = array();
		$options["headers"] = $this->getAuthHeaders();
		if(!is_null($body))
		{
			$options["body"] = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
			$this->logRequest($url, $options["body"]);
		}
		try
		{
			$this->response = $this->client->put($this->baseUrl . $url, $options);
			$this->logResponse($url, $this->response);
		}
		catch(BadResponseException $e)
		{
			$this->response = $e->getResponse();
			$this->logResponse($url, $this->response, Level::Error);
		}
		return $this->response;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $url
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	protected function get($url)
	{
		$options = array();
		$options["headers"] = $this->getAuthHeaders();
		$this->logRequest($url, null);
		try
		{
			$this->response = $this->client->get($this->baseUrl . $url, $options);
			$this->logResponse($url, $this->response);
		}
		catch(BadResponseException $e)
		{
			$this->response = $e->getResponse();
			$this->logResponse($url, $this->response, Level::Error);
		}
		return $this->response;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $url
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	protected function delete($url)
	{
		$options = array();
		$options["headers"] = $this->getAuthHeaders();
		$this->logRequest($url, null);
		try
		{
			$this->response = $this->client->delete($this->baseUrl . $url, $options);
			$this->logResponse($url, $this->response);
		}
		catch(BadResponseException $e)
		{
			$this->response = $e->getResponse();
			$this->logResponse($url, $this->response, Level::Error);
		}
		return $this->response;
	}
	// -----------------------------------------------------------------------------------------------------------------
	protected function logRequest($url, $body)
	{
		$context = array();
		$context["body"] = $body;
		$context["class"] = static::class;
		$this->loggerClassNama::info($url, $context);
	}
	// -----------------------------------------------------------------------------------------------------------------
	protected function logResponse($url, ResponseInterface $res, $level = Level::Info)
	{
		$context = array();
		$context["body"] = $res->getBody()->getContents();
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
			$resError = JsonSerializer::fromJson($json, $this->responseErrorClassNama);
			$err = current($resError->error);
			throw new BusinesException($err->description, intval($err->number));
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
		if(empty($json))
		{
			throw new BusinesException("BT:11002 Pusta odpowiedż: " . $res->getStatusCode() . " " . $res->getReasonPhrase(), 11002);
		}
		if($res->getStatusCode() == $successCode)
		{
			return JsonSerializer::arrayFromJson($json, $class);
		}
		else
		{
			$resError = JsonSerializer::fromJson($json, $this->responseErrorClassNama);
			$err = current($resError->error);
			throw new BusinesException($err->number . " " . $err->description);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------

}