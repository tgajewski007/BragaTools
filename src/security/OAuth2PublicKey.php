<?php
namespace braga\tools\security;
use GuzzleHttp\Client;
use braga\tools\exception\CantRetrivePublicKeyException;
trait OAuth2PublicKey
{
	const PUBLIC_KEYS_URL = "/protocol/openid-connect/certs";
	// -----------------------------------------------------------------------------------------------------------------
	public function getPublicKey($isseRealms, $kid)
	{
		$c = new Client();
		$res = $c->get($isseRealms . self::PUBLIC_KEYS_URL);
		if($res->getStatusCode() == 200)
		{
			$retval = new Jwk($res->getBody());
			return $retval->get($kid);
		}
		else
		{
			throw new CantRetrivePublicKeyException("BR:89201 Cant retrive public key", 89201);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
}
