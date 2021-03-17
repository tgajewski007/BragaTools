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
use braga\tools\exception\AuthenticationExcepion;
use braga\tools\exception\AuthorizationException;
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
	/**
	 * @var User
	 */
	private $user;
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
	public function setConfig(SecurityConfig $config)
	{
		$this->config = $config;
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
	 * @return \braga\tools\security\User
	 */
	public function getUser()
	{
		return $this->user;
	}
	// -----------------------------------------------------------------------------------------------------------------
	private function parseTokenString($tokenString)
	{
		$parser = new Parser(new JoseEncoder());
		$jwt = $parser->parse($tokenString);
		if($jwt instanceof Plain)
		{
			return $jwt;
		}
		else
		{
			throw new AuthenticationExcepion("BR:91001 Błąd parsowania tokenu");
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param Plain $token
	 * @throws AuthenticationExcepion
	 * @return \Lcobucci\JWT\Token\Plain
	 */
	private function valdateJwtToken(Plain $token)
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
				throw new AuthenticationExcepion("BR:91002 Nieobsugiwany algorytm weryfikacji tokenu", 91002);
			}
			$key = InMemory::plainText($this->config->getKey());
			$v = new Validator();
			$issuedBy = new IssuedBy($this->config->getIssuedBy());
			$validAt = new LooseValidAt(SystemClock::fromSystemTimezone());
			$signedWith = new SignedWith($signer, $key);
			if($v->validate($token, $issuedBy, $validAt, $signedWith))
			{
				return $token;
			}
			else
			{
				throw new AuthenticationExcepion("BR:91003 Błąd veryfikacji tokenu", 91003);
			}
		}
		else
		{
			throw new AuthenticationExcepion("BR:91004 Błędy typ tokenu", 91004);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @throws \Exception
	 * @return string
	 */
	private function getTokenStringFromHeader()
	{
		$headers = $this->getTokenStringFromHttpHeader();
		// HEADER: Get the access token from the header
		if(!empty($headers))
		{
			$matches = array();
			if(preg_match('/bearer\s(\S+)/i', $headers, $matches))
			{
				return $matches[1];
			}
		}
		throw new AuthenticationExcepion("BR:91005 Brak tokenu bearer w nagłówku Authorization", 90205);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @throws \Exception
	 * @return string
	 */
	private function getTokenStringFromHttpHeader()
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
		throw new AuthenticationExcepion("BR:91006 Brak nagłówka Authorization", 91006);
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @return \braga\tools\security\User
	 */
	public function authenticate()
	{
		$tokenString = $this->getTokenStringFromHeader();
		$this->jwt = $this->parseTokenString($tokenString);
		$this->valdateJwtToken($this->jwt);
		$this->user = new User($this->jwt);
		return $this->user;
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param string $roleName
	 * @throws \Exception
	 */
	public function authorize(?array ...$rolesName)
	{
		if(!empty($roleName))
		{
			$realmAccess = $this->jwt->claims()->get("resource_access");
			if(isset($realmAccess->{$this->config->getClientName()}))
			{
				$rolesArray = $realmAccess->{$this->config->getClientName()}->roles;

				$check = true;

				foreach($rolesName as $groupAndRoles)
				{
					foreach($groupAndRoles as $roleName)
					{
						$orCheck = false;
						if(array_search($roleName, $rolesArray) !== false)
						{
							$orCheck = $orCheck || true;
						}
					}
					$check = $check && $orCheck;
				}
				if(!$check)
				{
					throw new AuthorizationException("BR:91007 Brak dostępu", 91007);
				}
			}
			else
			{
				throw new AuthorizationException("BR:91008 Błąd autoryzacji", 91008);
			}
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param array ...$roleName
	 * @return \braga\tools\security\User
	 */
	public function check(?array ...$roleName)
	{
		$user = $this->authenticate();
		$this->authorize($roleName);
		return $user;
	}
	// -----------------------------------------------------------------------------------------------------------------
}

