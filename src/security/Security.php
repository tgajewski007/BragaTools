<?php
namespace braga\tools\security;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Rsa\Sha512;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Validator;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
/**
 * @author Toamsz Gajewski
 * error prefix BR:910
 */
class Security
{

	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var SecurityConfig
	 */
	private $config;
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var self
	 */
	private static $instance = null;
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @var Plain
	 */
	private $jwt;
	// -----------------------------------------------------------------------------------------------------------------
	private function __construct()
	{
	}
	// -----------------------------------------------------------------------------------------------------------------
	private function __clone()
	{
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return Perms
	 */
	public static function getInstance()
	{
		if(empty(self::$instance))
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return Plain
	 */
	public function getJwt()
	{
		return $this->jwt;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param Plain $jwt
	 */
	public function setJwt($jwt)
	{
		$this->jwt = $jwt;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $jwt
	 * @throws \Exception
	 * @return \Lcobucci\JWT\Token\Plain|\Lcobucci\JWT\Token
	 */
	private function getValidTokenFromString($jwt)
	{
		$parser = new Parser(new JoseEncoder());
		$token = $parser->parse($jwt);
		if($token instanceof Plain)
		{
			$typ = $token->claims()->get("typ");
			if($typ == "Bearer")
			{
				if($token->headers()->get("alg") == "RS256")
				{
					$signer = new Sha256();
				}
				elseif($token->headers()->get("alg") == "RS512")
				{
					$signer = new Sha512();
				}
				else
				{
					throw new \Exception("BR:91001 Nieobsugiwany algorytm weryfikacji tokenu", 91001);
				}
				$key = InMemory::plainText(KeyStore::get($token->headers()->get("kid"))->getPublicKey());

				$v = new Validator();
				$issuedBy = new IssuedBy(Config::getIssuerRealms());
				$validAt = new LooseValidAt(SystemClock::fromSystemTimezone());
				$signedWith = new SignedWith($signer, $key);
				if($v->validate($token, $issuedBy, $validAt, $signedWith))
				{
					return $token;
				}
				else
				{
					throw new \Exception("BR:91002 Błąd veryfikacji tokenu", 91002);
				}
			}
			else
			{
				throw new \Exception("BR:91003 Niewłaściwy typ tokenu", 91003);
			}
		}
		else
		{
			throw new \Exception("BR:91004 Błąd parsowania tokenu", 91004);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @throws \Exception
	 * @return string
	 */
	private function getTokenStringFromHttpHeader()
	{
		$headers = self::getAuthorizationHeader();
		// HEADER: Get the access token from the header
		if(!empty($headers))
		{
			$matches = array();
			if(preg_match('/bearer\s(\S+)/i', $headers, $matches))
			{
				return $matches[1];
			}
		}
		throw new \Exception("BR:91005 Brak tokenu w nagłówku", 90205);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @throws \Exception
	 * @return string
	 */
	private function getAuthorizationHeader()
	{
		if(isset($_SERVER['Authorization']))
		{
			return trim($_SERVER["Authorization"]);
		}
		else
		{
			if(isset($_SERVER['HTTP_AUTHORIZATION']))
			{ // Nginx or fast CGI
				return trim($_SERVER["HTTP_AUTHORIZATION"]);
			}
			elseif(function_exists('apache_request_headers'))
			{
				$requestHeaders = apache_request_headers();
				// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
				$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
				// print_r($requestHeaders);
				if(isset($requestHeaders['Authorization']))
				{
					return trim($requestHeaders['Authorization']);
				}
			}
		}
		throw new \Exception("BR:91006 Brak nagłówka Authorization", 91006);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return \braga\tools\security\User
	 */
	public function authenticate()
	{
		$retval = new User($idUser, $login, $fullName);
		return $retval;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $roleName
	 * @throws \Exception
	 */
	public function authorize(array ...$roleName)
	{
		if(!empty($roleName))
		{
			$realmAccess = self::getInstance()->jwt->claims()->get("resource_access");
			if(isset($realmAccess->{self::KEYCLOAK_CLIENT_NAME}))
			{
				if(array_search($roleName, $realmAccess->{self::KEYCLOAK_CLIENT_NAME}->roles) === false)
				{
					throw new \Exception("BR:91007 Błąd autoryzacji", 91007);
				}
			}
			else
			{
				throw new \Exception("BR:91008 Błąd autoryzacji", 91008);
			}
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $roleName
	 */
	public function check(array ...$roleName)
	{
		$tokenString = self::getTokenStringFromHttpHeader();
		$this->jwt = self::getValidTokenFromString($tokenString);
		$this->authorize($roleName);
	}
	// -----------------------------------------------------------------------------------------------------------------
}

