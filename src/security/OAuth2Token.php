<?php
namespace braga\tools\security;
use GuzzleHttp\Client;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use braga\tools\exception\AuthenticationExcepion;
use braga\tools\exception\CantRetriveTokenException;
use braga\tools\tools\JsonSerializer;
trait OAuth2Token
{
	private $publicClientAuthUrl = "/protocol/openid-connect/token";
	// -----------------------------------------------------------------------------------------------------------------
	public function createToken($isseRealms, $clientId, $clientSecret)
	{
		$jsonString = $this->makeRequest($isseRealms, $clientId, $clientSecret);
		/**
		 * @var AuthTokenResponse $obj
		 */
		$obj = JsonSerializer::fromJson($jsonString, AuthTokenResponse::class);
		$parser = new Parser(new JoseEncoder());
		$jwt = $parser->parse($obj->access_token);
		if($jwt instanceof \Lcobucci\JWT\Token\Plain)
		{
			return $jwt;
		}
		else
		{
			throw new AuthenticationExcepion("BR:91201 Błąd parsowania tokenu", 91201);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	private function makeRequest($isseRealms, $clientId, $clientSecret)
	{
		$c = new Client();
		$headers = [
						"Content-Type" => "application/x-www-form-urlencoded" ];
		$formsParams = [
						"grant_type" => "client_credentials",
						"client_id" => $clientId,
						"client_secret" => $clientSecret,
						"scope" => "profile email" ];
		$req = [
						"headers" => $headers,
						"form_params" => $formsParams ];
		$res = $c->post($isseRealms . $this->publicClientAuthUrl, $req);
		if($res->getStatusCode() == 200)
		{
			return $res->getBody();
		}
		else
		{
			throw new CantRetriveTokenException("BR:73101 Cant retrive token from auth server", 73101);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
}
